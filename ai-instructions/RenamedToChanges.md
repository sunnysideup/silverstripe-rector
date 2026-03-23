# Rename To

I want a custom Rector rule called `RenamedTo` for the latest Rector version.

The purpose is to add a TODO upgrade comment when code calls or overrides methods that were renamed, and the existing code likely needs manual review.

If there is a safe and reliable way to automatically rename usage, even better. However, the baseline requirement is to add TODO upgrade comments.

## Source data format

For each method rename, I have config like this:

```php
[
    'c' => 'Vendor\\Package\\SomeClass', // original class name
    'm' => 'oldMethodName', // old method name
    'n' => 'renamed to newMethodName()', // explanation/note
    'u' => false, // true = method name is unique enough to match even when receiver type cannot be resolved
],
```

## Meaning of fields

c = class name where the method rename applies (may be fully-qualified or short class name, depending on how it is stored in my config)

m = old method name (the method name to match in existing code)

n = human-readable upgrade note describing the rename target (for example `renamed to onInit()`)

u = whether it is safe to add the TODO when the class/type cannot be determined (because the method name is unique enough and unlikely to be a false positive)

## What the Rector rule should do

The rule must detect at least:

- instance method calls (including nullsafe calls)
- class method declarations that override/implement the renamed method in subclasses/traits/extensions

Implementation detail: the Rector rule may inspect whatever AST node types are appropriate to achieve this.

## Transformation required (method calls)

If code calls a renamed method on an instance of the configured class (or subclass), add a TODO doc comment immediately before the call.

This is a manual review marker. The rule does not need to automatically rename the call.

Before

```php
$extension->MetaTags($params);
```

After

```php
/** @TODO SSU RECTOR UPGRADE TASK - TractorCow\Fluent\Extension\FluentSiteTreeExtension::MetaTags: renamed to updateMetaTags() */
$extension->MetaTags($params);
```

## Transformation required (method overrides)

If a class method overrides or implements a renamed method from the configured class (or subclass relationship applies), add a TODO doc comment immediately before the method declaration.

Before

```php
function init()
{
    // ...
}
```

After

```php
/** @TODO SSU RECTOR UPGRADE TASK - SilverStripe\MFA\Extension\RequirementsExtension::init: renamed to onInit() */
function init()
{
    // ...
}
```

## Why overrides need review

Renamed hooks and extension points often require the method name itself to change in project code, not just call sites. This rule should flag overrides/implementations for manual review.

## Optional enhancement (best-effort only)

If feasible, the rule may attempt a conservative auto-fix in limited cases by renaming the method call or method declaration name when the target renamed method can be reliably extracted from `n`.

For example, parse notes like:

- `renamed to onInit()`
- `renamed to updateMetaTags()`
- `renamed to RecordList`

If auto-fix is implemented:

- it must be conservative and safe
- it must preserve formatting/comments as much as possible
- it must still be idempotent
- it should still add a TODO when confidence is low or when manual follow-up is likely

Auto-fix is optional. TODO annotation is the required behavior.

## Matching rules

### A) Method calls (instance calls, including nullsafe calls)

Match when:

- The method name equals `m`, and
- The receiver object type can be resolved and is `c` or a subclass of `c`

### B) Unknown receiver type fallback (method calls only)

If the receiver type cannot be resolved, only match when:

- method name equals `m`, and
- `u === true`

If `u === false`, do not annotate unknown-type calls.

### C) Method declarations (overrides / implementations)

Match when:

- The method name equals `m`, and
- The containing class is a subclass of `c` (or otherwise overrides / implements that method in the PHP inheritance chain)

If this is hard to resolve perfectly, a best-effort approach is acceptable, but please document the limitation.

### D) Handling Short Class Names vs FQCNs

The class name c in the configuration might be a fully qualified class name (FQCN) or a short name. Please implement the matching logic to accommodate both. For example, use Rector's built-in type checking (like ObjectType or isObjectType()) and fallback to checking if the node's resolved class type ends with the configured string (e.g., using str_ends_with()).

## Important constraints

### name space setup

The namespace is as follows:

Rector rule: `namespace Netwerkstatt\SilverstripeRector\Rector\Methods;`
Rector tests: `namespace Netwerkstatt\SilverstripeRector\Tests\Methods\XXX` where XXX is the name of the Rector Rule.

In the tests, I have set up the following folders / files:

- `config/configured_rule.php`
- `Fixtures/fixture.php.inc`

### Configuration Injection

The rule must implement `Rector\Contract\Rector\ConfigurableRectorInterface` to receive the array of method signature changes. Do not hardcode the configuration array inside the rule class itself.

### Idempotency

The rule must be idempotent:

- Do not add duplicate TODO comments if the same TODO already exists.

### Existing docblocks

If a `ClassMethod` already has a docblock:

- append a new `@TODO SSU RECTOR UPGRADE TASK - ...` line to the existing docblock (preferred), or
- otherwise preserve the existing docblock content and add the TODO without destroying it.

Please do not replace/remove existing docblocks.

## Exact comment text format

Use exactly this format:

```php
@TODO SSU RECTOR UPGRADE TASK - {ClassName}::{methodName}: {note}
```

Example:

```php
@TODO SSU RECTOR UPGRADE TASK - SilverStripe\CMS\Controllers\CMSMain::PageList: renamed to RecordList
```

## What I want in the answer

Please provide:

- Full Rector rule class (`RenamedTo`)
- Any helper methods/classes needed
- Example Rector config registration
- Notes about required Rector services/packages (if any)
- Fixture tests (preferred) covering at least:

  - known typed method call (should annotate)
  - unknown typed method call + fallback safe = true (should annotate)
  - unknown typed method call + fallback safe = false (should not annotate)
  - overriding class method in subclass (should annotate)
  - already annotated code (should not duplicate)
  - class method with existing docblock (should append TODO, not replace)

If optional auto-fix is implemented, include fixtures for:

- safe call rename
- safe method declaration rename
- ambiguous note format (should annotate only, no rename)

## Environment / assumptions

- I am using the latest Rector version
- I can install additional composer packages if needed

## Actual Data

Below is the data we have. I will add the `u` value later. This gives a good idea of what to expect.

```php
private const LIST =
[
    [
        'c' => 'DNADesign\\Elemental\\Extensions\\ElementalPageExtension',
        'm' => 'MetaTags',
        'n' => 'renamed to updateMetaTags()',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
        'm' => 'PageList',
        'n' => 'renamed to RecordList',
    ],
    [
        'c' => 'SilverStripe\\ORM\\CMSPreviewable',
        'm' => 'CMSEditLink',
        'n' => 'renamed to getCMSEditLink()',
    ],
    [
        'c' => 'SilverStripe\\ORM\\Hierarchy\\Hierarchy',
        'm' => 'flushCache',
        'n' => 'renamed to onFlushCache()',
    ],
    [
        'c' => 'SilverStripe\\Security\\InheritedPermissionFlusher',
        'm' => 'flushCache',
        'n' => 'renamed to onFlushCache()',
    ],
    [
        'c' => 'SilverStripe\\MFA\\Extension\\MemberExtension',
        'm' => 'afterMemberLoggedIn',
        'n' => 'renamed to onAfterMemberLoggedIn()',
    ],
    [
        'c' => 'SilverStripe\\MFA\\Extension\\RequirementsExtension',
        'm' => 'init',
        'n' => 'renamed to onInit()',
    ],
    [
        'c' => 'SilverStripe\\SessionManager\\Extensions\\RememberLoginHashExtension',
        'm' => 'onAfterRenewToken',
        'n' => 'renamed to onAfterRenewSession()',
    ],
    [
        'c' => 'SilverStripe\\Subsites\\Extensions\\SiteTreeSubsites',
        'm' => 'MetaTags',
        'n' => 'renamed to updateMetaTags()',
    ],
    [
        'c' => 'SilverStripe\\Versioned\\Versioned',
        'm' => 'flushCache',
        'n' => 'renamed to onFlushCache()',
    ],
    [
        'c' => 'TractorCow\\Fluent\\Extension\\FluentLeftAndMainExtension',
        'm' => 'init',
        'n' => 'renamed to onInit()',
    ],
    [
        'c' => 'TractorCow\\Fluent\\Extension\\FluentSiteTreeExtension',
        'm' => 'MetaTags',
        'n' => 'renamed to updateMetaTags()',
    ],
    [
        'c' => 'TractorCow\\Fluent\\Extension\\FluentVersionedExtension',
        'm' => 'flushCache',
        'n' => 'renamed to onFlushCache()',
    ],
];
```
