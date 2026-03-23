# Use Instead

I want a custom Rector rule called `UseInstead` for the latest Rector version.

The purpose is to add a TODO upgrade comment when code calls or overrides methods that are deprecated/changed and should use an alternative method or API instead, and the existing code likely needs manual review.

In some cases, an automatic rename or replacement may be possible, but the baseline requirement is to add TODO upgrade comments because replacements are often contextual.

## Source data format

For each change, I have config like this:

```php
[
    'c' => 'Vendor\\Package\\SomeClass', // original class name
    'm' => 'doThing', // method name
    'n' => 'use doThingNew() instead.', // explanation/note
    'u' => false, // true = method name is unique enough to match even when receiver type cannot be resolved
],
```

## Meaning of fields

c = class name where the method should no longer be used (may be fully-qualified or short class name, depending on how it is stored in my config)

m = method name

n = human-readable upgrade note describing what to use instead

u = whether it is safe to add the TODO when the class/type cannot be determined (because the method name is unique enough and unlikely to be a false positive)

## What the Rector rule should do

The rule must detect at least:

- instance method calls (including nullsafe calls)
- class method declarations that override changed parent methods

Implementation detail: the Rector rule may inspect whatever AST node types are appropriate to achieve this.

## Transformation required (method calls)

If code calls a changed method on an instance of the configured class (or subclass), add a TODO doc comment immediately before the call.

This is a manual review marker. The rule does not need to automatically rewrite the call to the replacement API.

Before

```php
$value = $service->oldMethod($arg);
```

After

```php
/** @TODO SSU RECTOR UPGRADE TASK - SomeClass::oldMethod: use newMethod() instead. */
$value = $service->oldMethod($arg);
```

### Why method calls need review

“Use X instead” changes are often not simple renames. The replacement may:

- live on another class/service
- require different arguments
- return a different type
- require a different call sequence
- be template-only vs PHP-only (or vice versa)

This rule should flag call sites for manual review.

## Transformation required (method overrides)

If a class method overrides a changed method from the configured class (or subclass relationship applies), add a TODO doc comment immediately before the method declaration.

Before

```php
function oldMethod($arg)
{
    // ...
}
```

After

```php
/** @TODO SSU RECTOR UPGRADE TASK - SomeClass::oldMethod: use newMethod() instead. */
function oldMethod($arg)
{
    // ...
}
```

### Why overrides need review

If a parent API now expects a different extension point or replacement method, subclass overrides may no longer be called, or may need to be migrated to a different hook/method entirely. This rule should flag overrides for manual review.

## Optional enhancement (best-effort only)

If feasible, the rule may attempt a conservative auto-fix in very limited cases, for example:

- the note clearly indicates a direct rename on the same receiver (e.g. `use fooBar() instead`)
- no argument/order changes are implied
- no receiver/context migration is implied

If auto-fix is implemented:

- it must be conservative and safe
- it must preserve formatting/comments as much as possible
- it must still be idempotent
- it should still add a TODO when confidence is low

Auto-fix is optional. TODO annotation is the required behavior.

## Matching rules

### A) Method calls (`MethodCall`, `NullsafeMethodCall`, `StaticCall` )

Match when:

- The method name equals `m`, and
- The receiver object type can be resolved and is `c` or a subclass of `c`

### B) Unknown receiver type fallback (method calls only)

If the receiver type cannot be resolved, only match when:

- method name equals `m`, and
- `u === true`

If `u === false`, do not annotate unknown-type calls.

### C) Method declarations (`ClassMethod`)

Match when:

- The method name equals `m`, and
- The containing class is a subclass of `c` (or otherwise overrides that method in the PHP inheritance chain)

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
@TODO SSU RECTOR UPGRADE TASK - SomeClass::oldMethod: use newMethod() instead.
```

## What I want in the answer

Please provide:

- Full Rector rule class (`UseInstead`)
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

## Environment / assumptions

- I am using the latest Rector version
- I can install additional composer packages if needed

## Actual Data

Below is the data we have. I will add the `u` value later. This gives you a good idea of what to expect.

```php
private const LIST =
[
    [
        'c' => 'DNADesign\\Elemental\\Models\\BaseElement',
        'm' => 'getDescription',
        'n' => 'use i18n_classDescription() instead.',
    ],

    [
        'c' => 'SilverStripe\\Admin\\LeftAndMain',
        'm' => 'currentPage',
        'n' => 'use currentRecord() instead.',
    ],
    [
        'c' => 'SilverStripe\\Admin\\LeftAndMain',
        'm' => 'currentPageID',
        'n' => 'use currentRecordID() instead.',
    ],
    [
        'c' => 'SilverStripe\\Admin\\LeftAndMain',
        'm' => 'isCurrentPage',
        'n' => 'use isCurrentRecord() instead.',
    ],
    [
        'c' => 'SilverStripe\\Admin\\LeftAndMain',
        'm' => 'setCurrentPageID',
        'n' => 'use setCurrentRecordID() instead.',
    ],

    [
        'c' => 'SilverStripe\\AssetAdmin\\Extensions\\RemoteFileModalExtension',
        'm' => 'getRequest',
        'n' => 'use $this->getOwner()->getRequest() instead.',
    ],

    [
        'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
        'm' => 'CanOrganiseSitetree',
        'n' => 'use canOrganiseTree instead.',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
        'm' => 'LinkPageAdd',
        'n' => 'use LinkRecordAdd() instead.',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
        'm' => 'LinkPageEdit',
        'n' => 'use LinkRecordEdit() instead.',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
        'm' => 'LinkPageHistory',
        'n' => 'use LinkRecordHistory() instead.',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
        'm' => 'LinkPageSettings',
        'n' => 'use LinkRecordSettings() instead.',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
        'm' => 'LinkPages',
        'n' => 'use LinkRecords instead',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
        'm' => 'LinkPagesWithSearch',
        'n' => 'use LinkRecordsWithSearch instead',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
        'm' => 'PageTypes',
        'n' => 'use RecordTypes() instead.',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
        'm' => 'SiteTreeAsUL',
        'n' => 'use TreeAsUL() instead.',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
        'm' => 'SiteTreeHints',
        'n' => 'use TreeHints() instead.',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
        'm' => 'getPageTypes',
        'n' => 'use getRecordTypes() instead.',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
        'm' => 'getSiteTreeFor',
        'n' => 'use getTreeFor() instead.',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
        'm' => 'performPublish',
        'n' => 'use RecursivePublishable::publishRecursive() instead.',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Controllers\\ContentController',
        'm' => 'Menu',
        'n' => 'use getMenu() instead. You can continue to use $Menu in templates.',
    ],

    [
        'c' => 'SilverStripe\\Dev\\Deprecation',
        'm' => 'withNoReplacement',
        'n' => 'use withSuppressedNotice() instead',
    ],
    [
        'c' => 'SilverStripe\\Dev\\DevelopmentAdmin',
        'm' => 'get_links',
        'n' => 'use getLinks() instead to include permission checks',
    ],
    [
        'c' => 'SilverStripe\\Forms\\Form',
        'm' => 'validationResult',
        'n' => 'use validate() instead',
    ],
    [
        'c' => 'SilverStripe\\Forms\\FormField',
        'm' => 'extendValidationResult',
        'n' => 'use extend() directly instead',
    ],
    [
        'c' => 'SilverStripe\\Security\\InheritedPermissions',
        'm' => 'getJoinTable',
        'n' => 'use getGroupJoinTable() instead',
    ],
    [
        'c' => 'SilverStripe\\View\\SSViewer',
        'm' => 'get_base_tag',
        'n' => 'use getBaseTag() instead',
    ],

    [
        'c' => 'SilverStripe\\StaticPublishQueue\\Extension\\Engine\\SiteTreePublishingEngine',
        'm' => 'getToDelete',
        'n' => 'use getUrlsToDelete() instead',
    ],
    [
        'c' => 'SilverStripe\\StaticPublishQueue\\Extension\\Engine\\SiteTreePublishingEngine',
        'm' => 'getToUpdate',
        'n' => 'use getUrlsToUpdate() instead',
    ],
    [
        'c' => 'SilverStripe\\StaticPublishQueue\\Extension\\Engine\\SiteTreePublishingEngine',
        'm' => 'setToDelete',
        'n' => 'use setUrlsToDelete() instead',
    ],
    [
        'c' => 'SilverStripe\\StaticPublishQueue\\Extension\\Engine\\SiteTreePublishingEngine',
        'm' => 'setToUpdate',
        'n' => 'use setUrlsToUpdate() instead',
    ],

    [
        'c' => 'SilverStripe\\VendorPlugin\\Methods\\CopyMethod',
        'm' => 'copy',
        'n' => 'use Filesystem::copy instead',
    ],

    [
        'c' => 'SilverStripe\\Versioned\\Versioned',
        'm' => 'canArchive',
        'n' => 'use canDelete() instead.',
    ],

    [
        'c' => 'Symbiote\\QueuedJobs\\Tasks\\ProcessJobQueueTask',
        'm' => 'getQueue',
        'n' => 'use AbstractQueuedJob::getQueue() instead',
    ],
];
```
