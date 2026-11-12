#!/usr/bin/env bash
#
# sync-upstream.sh: A to Z upstream sync for sunnysideup/silverstripe-rector
#
# Brings wernerkrauss/silverstripe-rector (upstream) main into sunnysideup/silverstripe-rector (fork) main.
#
#   A. Checks the tools you need (git, composer, php, opencode)
#   B. Finds your checkout, or clones your fork if there isn't one
#   C. Adds the "upstream" remote and fetches both repos
#   D. Merges upstream/main into a fresh branch (sync-upstream-YYYYMMDD-HHMM)
#   E. Hands any conflicts to opencode, which resolves them without asking questions
#   F. Checks for leftover conflict markers, runs composer install + phpunit
#      (phpunit is the gate; phpcs, phpstan and rector --dry-run are shown as advice)
#   G. Shows you a summary and asks once: commit, update main and push? [y/N]
#
# Usage:
#   scripts/sync-upstream.sh             # the whole thing, A to Z
#   scripts/sync-upstream.sh --no-ai     # stop at the conflicts so you can fix them yourself
#   scripts/sync-upstream.sh --resume    # after fixing by hand (or re-running opencode): F + G
#   scripts/sync-upstream.sh --abort     # throw the attempt away and go back to main
#   scripts/sync-upstream.sh --yes       # don't ask at G, just commit + push (only if tests pass)
#
# Optional env vars:
#   REPO_DIR        where your checkout is / should be cloned to
#                   (default: the checkout this script lives in, otherwise ./silverstripe-rector)
#   FORK_URL        default: git@github.com:sunnysideup/silverstripe-rector.git
#   MAIN_BRANCH     default: main
#   UPSTREAM_BRANCH default: main
#   OPENCODE_MODEL  e.g. anthropic/claude-sonnet-4-5 (default: opencode's configured model)
#
# Notes:
#   - The script never force-pushes. If the push is rejected (e.g. the docs workflow pushed a
#     "[DOCS] Generate documentation" commit meanwhile) it tells you what to do; nothing is lost.
#   - docs/*.md and docs/*.json are generated. On conflicts they are regenerated, not hand-merged.

set -euo pipefail

FORK_URL="${FORK_URL:-git@github.com:sunnysideup/silverstripe-rector.git}"
FORK_URL_HTTPS="https://github.com/sunnysideup/silverstripe-rector.git"
UPSTREAM_URL="https://github.com/wernerkrauss/silverstripe-rector.git"
MAIN_BRANCH="${MAIN_BRANCH:-main}"
UPSTREAM_BRANCH="${UPSTREAM_BRANCH:-main}"
MERGE_BRANCH="sync-upstream-$(date +%Y%m%d-%H%M)"
MARKER_RE='^(<<<<<<<|=======|>>>>>>>|\|\|\|\|\|\|\|)( |$)'
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

MODE="full"     # full | resume | abort
USE_AI=true
ASSUME_YES=false

c_info() { printf '\e[36m==> %s\e[0m\n' "$*"; }
c_ok()   { printf '\e[32m✔  %s\e[0m\n' "$*"; }
c_warn() { printf '\e[33m!  %s\e[0m\n' "$*"; }
c_err()  { printf '\e[31m✘  %s\e[0m\n' "$*" >&2; }
die()    { c_err "$*"; exit 1; }

for arg in "$@"; do
    case "$arg" in
        --no-ai)  USE_AI=false ;;
        --resume) MODE="resume" ;;
        --abort)  MODE="abort" ;;
        --yes|-y) ASSUME_YES=true ;;
        -h|--help) sed -n '2,34p' "${BASH_SOURCE[0]}"; exit 0 ;;
        *) die "Unknown option: $arg (try --help)" ;;
    esac
done

# =============================================================================
# A. Tools
# =============================================================================
check_tools() {
    command -v git >/dev/null      || die "git is not installed (sudo apt install git)"
    command -v php >/dev/null      || die "php is not installed"
    command -v composer >/dev/null || die "composer is not installed"
    if $USE_AI && ! command -v opencode >/dev/null; then
        c_warn "opencode not found, so conflicts will have to be resolved by hand (--no-ai mode)."
        USE_AI=false
    fi
}

# =============================================================================
# B. Find or clone the fork
# =============================================================================
is_fork_checkout() {
    git -C "$1" rev-parse --is-inside-work-tree >/dev/null 2>&1 \
        && git -C "$1" remote get-url origin 2>/dev/null | grep -qi 'sunnysideup/silverstripe-rector'
}

locate_repo() {
    if [[ -z "${REPO_DIR:-}" ]]; then
        if is_fork_checkout "$SCRIPT_DIR"; then
            REPO_DIR="$(git -C "$SCRIPT_DIR" rev-parse --show-toplevel)"
        elif is_fork_checkout "$PWD"; then
            REPO_DIR="$(git rev-parse --show-toplevel)"
        else
            REPO_DIR="$PWD/silverstripe-rector"
        fi
    fi

    if [[ ! -d "$REPO_DIR/.git" ]]; then
        [[ "$MODE" != "full" ]] && die "No checkout found at $REPO_DIR"
        [[ -e "$REPO_DIR" ]] && die "$REPO_DIR exists but is not a git repo; move it or set REPO_DIR"
        c_info "Cloning your fork into $REPO_DIR ..."
        if ! git clone "$FORK_URL" "$REPO_DIR" 2>/dev/null; then
            c_warn "SSH clone failed; falling back to HTTPS (pushing will ask for a GitHub token)."
            git clone "$FORK_URL_HTTPS" "$REPO_DIR"
        fi
    fi

    cd "$REPO_DIR"
    c_info "Working in $REPO_DIR"
}

# =============================================================================
# Abort
# =============================================================================
do_abort() {
    git merge --abort 2>/dev/null || true
    local current; current="$(git branch --show-current)"
    git checkout --quiet "$MAIN_BRANCH"
    if [[ "$current" == sync-upstream-* ]]; then
        git branch -D "$current"
    fi
    c_ok "Aborted. Back on $MAIN_BRANCH, nothing changed."
}

# =============================================================================
# C. Remotes + fetch
# =============================================================================
prepare() {
    [[ -z "$(git status --porcelain)" ]] || die "Working tree is not clean. Commit or stash first (git stash -u)."

    # Better conflict handling; repo-local only
    git config rerere.enabled true          # remember resolutions for next sync
    git config rerere.autoupdate true
    git config merge.conflictstyle zdiff3   # show the common ancestor inside conflicts

    if git remote get-url upstream >/dev/null 2>&1; then
        git remote set-url upstream "$UPSTREAM_URL"
    else
        git remote add upstream "$UPSTREAM_URL"
    fi

    c_info "Fetching origin and upstream..."
    git fetch --quiet origin
    git fetch --quiet upstream --tags

    git checkout --quiet "$MAIN_BRANCH"
    git pull --quiet --ff-only origin "$MAIN_BRANCH"

    local behind ahead
    behind=$(git rev-list --count "HEAD..upstream/$UPSTREAM_BRANCH")
    ahead=$(git rev-list --count "upstream/$UPSTREAM_BRANCH..HEAD")
    c_info "Upstream has $behind new commit(s); your fork has $ahead of its own."

    if [[ "$behind" -eq 0 ]]; then
        c_ok "Nothing to merge. You are up to date."
        exit 0
    fi
}

# =============================================================================
# D. Merge
# =============================================================================
do_merge() {
    git checkout --quiet -b "$MERGE_BRANCH"
    c_info "Merging upstream/$UPSTREAM_BRANCH into $MERGE_BRANCH ..."

    if git merge --no-ff --no-commit "upstream/$UPSTREAM_BRANCH" >/dev/null 2>&1; then
        c_ok "Merged cleanly, no conflicts."
    else
        c_warn "Merge has conflicts (expected). Everything else is already merged."
    fi

    mapfile -t DELETED < <(git diff --cached --name-only --diff-filter=D)
    if (( ${#DELETED[@]} )); then
        c_warn "Upstream deleted these files (deletion accepted; restore any you still need):"
        printf '     %s\n' "${DELETED[@]}"
        echo "     restore with: git checkout $MAIN_BRANCH -- <file>"
    fi
}

# =============================================================================
# E. Conflicts -> opencode
# =============================================================================
resolve_conflicts() {
    mapfile -t CONFLICTS < <(git diff --name-only --diff-filter=U)
    (( ${#CONFLICTS[@]} )) || return 0

    c_warn "${#CONFLICTS[@]} conflicted file(s):"
    local f
    for f in "${CONFLICTS[@]}"; do
        printf '     %-70s %s hunk(s)\n' "$f" "$(grep -c '^<<<<<<<' "$f" || true)"
    done

    PROMPT_FILE="$(git rev-parse --absolute-git-dir)/MERGE_AI_PROMPT.md"   # inside .git, never committed
    cat > "$PROMPT_FILE" <<EOF
You are resolving git merge conflicts in a PHP 8.3 Rector extension for Silverstripe CMS
(sunnysideup/silverstripe-rector, a fork of wernerkrauss/silverstripe-rector).

A merge of upstream/$UPSTREAM_BRANCH into our branch is in progress. These files have conflicts:

$(printf -- '- %s\n' "${CONFLICTS[@]}")

Conflict markers use zdiff3 style:
  <<<<<<< HEAD          = OUR fork (sunnysideup)
  ||||||| base          = the common ancestor
  =======
  >>>>>>> upstream/...  = THEIR upstream (wernerkrauss)

Project facts:
- Minimum PHP is 8.3 (composer.json). Keep PHP 8.3 syntax; do not downgrade code to PHP 7.4 style.
  If upstream still targets older PHP in composer.json / rector.php / phpstan.neon / CI, keep OUR 8.3 settings.
- Every Rector rule must extend AbstractRector, implement Rector\RuleDoc\Contract\DocumentedRuleInterface
  and use Rector\RuleDoc\* classes (not Symplify\RuleDocGenerator\*). The method is getRuleDefinition().
- docs/all_rectors_overview.md, docs/rector-rules.json, docs/rectors-and-setlists.json are GENERATED.
  Do not hand-merge them: take either side, then run
  composer docs:generate && composer docs:export && composer docs:export:rules
- tests/Set/SetListIntegrityTest.php checks every constant in src/Set/*.php points to a config that loads.
  Never remove public constants from SilverstripeSetList / SilverstripeLevelSetList.

Rules. Do NOT ask me questions; make the call yourself:
1. KEEP BOTH SIDES wherever possible. If each side added different constants, rules, config
   entries, use statements or methods, keep all of them (no duplicates).
2. If both sides changed the SAME thing differently, prefer UPSTREAM's version, unless ours adds
   behaviour upstream lacks; then merge that behaviour into upstream's structure.
3. If a class/rule was renamed or moved upstream, use the upstream name and make sure our
   references (config files, set lists, tests) point to it.
4. In config/*.php rule lists: union of both lists, keep the file's existing ordering style,
   no duplicate rules. Every referenced class must actually exist in src/.
5. Remove ALL conflict markers. Do not touch non-conflicted files unless needed to make the
   code/tests work.
6. When finished, run:
     grep -rnE '$MARKER_RE' src config tests || echo clean
     composer install --no-interaction
     vendor/bin/phpunit
   and fix anything YOUR resolution broke. Failures that also happen on plain upstream can be
   left; just list them. Also try 'composer lint' and 'composer stan' and fix what your resolution broke.
7. Stage the resolved files with git add. Do NOT commit and do NOT push.
8. End with a short summary: per file, what you kept from each side.
EOF

    if $USE_AI; then
        c_info "Handing the conflicts to opencode (this can take a few minutes)..."
        local model_args=()
        [[ -n "${OPENCODE_MODEL:-}" ]] && model_args=(--model "$OPENCODE_MODEL")
        opencode run "${model_args[@]}" "$(cat "$PROMPT_FILE")" \
            || c_warn "opencode exited non-zero; checking what it left behind..."
    else
        echo
        c_info "Prompt for an AI is in: $PROMPT_FILE"
        echo "  Resolve the conflicts, by hand or with:  opencode run \"\$(cat $PROMPT_FILE)\""
        echo "  then run:  $0 --resume"
        exit 2
    fi
}

# =============================================================================
# F. Verify + test
# =============================================================================
TESTS_OK=false
verify() {
    git rev-parse -q --verify MERGE_HEAD >/dev/null \
        || die "No merge in progress here. Run without --resume to start one."
    MERGE_BRANCH="$(git branch --show-current)"

    c_info "Checking for leftover conflict markers..."
    # docs/ only gets the unambiguous markers: a bare "=======" can be a markdown heading underline
    if grep -rnE "$MARKER_RE" src config tests scripts 2>/dev/null \
       || grep -rnE '^(<<<<<<<|>>>>>>>) ' docs 2>/dev/null; then
        c_err "Conflict markers still present (above). Fix them (or re-run opencode), then: $0 --resume"
        exit 3
    fi
    c_ok "No conflict markers."

    # Anything still flagged unmerged has no markers left, so stage it
    git diff --name-only --diff-filter=U | xargs -r git add

    c_info "composer install..."
    composer install --no-interaction --quiet

    c_info "Running phpunit..."
    if vendor/bin/phpunit --no-progress; then
        c_ok "Tests pass."
        TESTS_OK=true
    else
        c_warn "Some tests fail. Check whether they also fail on plain upstream before deciding."
    fi

    # Advisory only: these do not decide whether we push, but you should know about them
    c_info "Advisory checks (phpcs, phpstan, rector --dry-run)..."
    local check
    for check in "vendor/bin/phpcs -q" "vendor/bin/phpstan analyse --no-progress -q" \
                 "vendor/bin/rector --dry-run --no-ansi --no-progress-bar"; do
        if $check >/dev/null 2>&1; then
            c_ok "${check%% *}: clean"
        else
            c_warn "${check%% *}: reports issues (run '$check' to see them; 'composer ci' runs all of them)"
        fi
    done
}

# =============================================================================
# G. Review + commit + push
# =============================================================================
finish() {
    echo
    c_info "Summary of what this merge changes compared with your $MAIN_BRANCH:"
    git diff --cached --stat "$MAIN_BRANCH" | tail -25
    echo
    echo "  For a closer look first (in another terminal):  cd $REPO_DIR && git diff --cached $MAIN_BRANCH"
    echo

    local answer="n"
    if $ASSUME_YES; then
        if $TESTS_OK; then
            answer="y"
        else
            c_warn "--yes given but tests failed, so not pushing automatically."
        fi
    else
        local q="Commit, update $MAIN_BRANCH and push to origin? [y/N] "
        $TESTS_OK || q="Tests FAILED. Commit and push anyway? [y/N] "
        read -r -p "$q" answer || true
    fi

    if [[ "$answer" =~ ^[Yy]$ ]]; then
        git commit --quiet --no-edit
        git checkout --quiet "$MAIN_BRANCH"
        git merge --quiet --ff-only "$MERGE_BRANCH"
        git branch -d "$MERGE_BRANCH" >/dev/null

        if git push origin "$MAIN_BRANCH"; then
            c_ok "Done. $MAIN_BRANCH now includes upstream and is pushed."
            echo "  Next: run 'composer update sunnysideup/silverstripe-rector' in your projects."
        else
            c_warn "Push was rejected. $MAIN_BRANCH is merged locally and nothing is lost."
            echo "  Usually origin got a new commit meanwhile (e.g. '[DOCS] Generate documentation')."
            echo "  See it:      git fetch origin && git log --oneline $MAIN_BRANCH..origin/$MAIN_BRANCH"
            echo "  Integrate:   git pull --rebase origin $MAIN_BRANCH   (regenerate docs if they conflict:"
            echo "               composer docs:generate && composer docs:export && composer docs:export:rules)"
            echo "  Then push:   git push origin $MAIN_BRANCH   (never --force)"
            exit 4
        fi
    else
        c_info "Left uncommitted on branch $MERGE_BRANCH. When ready:"
        echo "     cd $REPO_DIR && $0 --resume      (re-checks, then asks again)"
        echo "  or $0 --abort                        (throw it away)"
    fi
}

# =============================================================================
# Run
# =============================================================================
check_tools
locate_repo

case "$MODE" in
    abort)  do_abort ;;
    resume) verify; finish ;;
    full)   prepare; do_merge; resolve_conflicts; verify; finish ;;
esac
