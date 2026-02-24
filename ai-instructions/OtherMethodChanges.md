# Other Method Changes

I want a custom Rector rule called `OtherMethod` for the latest Rector version.

The purpose is to add a TODO upgrade comment when code references methods, traits, or APIs that changed in incompatible ways (removed methods, static/non-static changes, abstract method requirements, or other manual upgrade actions), and the existing code likely needs manual review.

## Source data format

For each change, I have config like this:

```php
[
    'c' => 'SilverStripe\\Core\\Manifest\\VersionProvider', // original class/trait name
    'm' => 'getComposerLock', // method name (may be empty for class/trait-level removals)
    'n' => 'has been replaced by composer-runtime-api', // explanation/note
    'u' => false, // true = method name is unique enough to match even when receiver type cannot be resolved
],
```

## Meaning of fields

c = class or trait name where the change applies (may be fully-qualified or short class name, depending on how it is stored in my config)

m = method name; may be empty (`''`) for class/trait-level changes (for example removed trait with no specific method target)

n = human-readable upgrade note (may describe removals, static/non-static changes, abstract requirements, replacement APIs, or other migration instructions)

u = whether it is safe to add the TODO when the class/type cannot be determined (because the method name is unique enough and unlikely to be a false positive)

## What the Rector rule should do

The rule must detect at least:

- instance method calls (including nullsafe calls)
- static method calls
- class method declarations that override changed parent methods
- trait uses for removed traits (when `m` is empty and `c` refers to a trait)

Implementation detail: the Rector rule may inspect whatever AST node types are appropriate to achieve this.

## Transformation required (method calls)

If code calls a changed method on the configured class (or subclass), add a TODO doc comment immediately before the call.

This is a manual review marker. The rule does not need to automatically rewrite the code.

Before

```php
$provider->getComposerLock();
```

After

```php
/** @TODO UPGRADE TASK - SilverStripe\Core\Manifest\VersionProvider::getComposerLock: has been replaced by composer-runtime-api */
$provider->getComposerLock();
```

## Transformation required (static method calls)

If code makes a static call for a changed method, add a TODO doc comment immediately before the static call.

This is especially relevant for changes like:

- method is now static
- method is no longer static

Before

```php
SiteTree::getPermissionChecker();
```

After

```php
/** @TODO UPGRADE TASK - SiteTree::getPermissionChecker: Method SiteTree::getPermissionChecker() is no longer static */
SiteTree::getPermissionChecker();
```

## Transformation required (method overrides / declarations)

If a class method overrides a changed method from the configured class (or subclass relationship applies), add a TODO doc comment immediately before the method declaration.

This includes changes such as:

- method removed
- method now abstract
- method signature/behavior changed
- method staticness changed (manual review still required)

Before

```php
function up()
{
    // ...
}
```

After

```php
/** @TODO UPGRADE TASK - MigrationTask::up: Method MigrationTask::up() is now abstract */
function up()
{
    // ...
}
```

## Transformation required (removed traits / class-level changes)

If an entry has `m === ''`, treat it as a class/trait-level change.

At minimum, the rule should detect trait usage (for removed traits) and add a TODO doc comment immediately before the `use` statement inside the class.

Before

```php
use SessionEnvTypeSwitcher;
```

After

```php
/** @TODO UPGRADE TASK - SilverStripe\Control\Middleware\URLSpecialsMiddleware\SessionEnvTypeSwitcher: Removed deprecated trait SilverStripe\Control\Middleware\URLSpecialsMiddleware\SessionEnvTypeSwitcher - removed without equivalent functionality to replace it */
use SessionEnvTypeSwitcher;
```

If perfect trait-name resolution is difficult, a best-effort approach is acceptable, but please document the limitation.

## Matching rules

### A) Instance method calls (`MethodCall`, `NullsafeMethodCall`)

Match when:

- `m` is not empty, and
- The method name equals `m`, and
- The receiver object type can be resolved and is `c` or a subclass of `c`

### B) Unknown receiver type fallback (instance calls only)

If the receiver type cannot be resolved, only match when:

- `m` is not empty, and
- method name equals `m`, and
- `u === true`

If `u === false`, do not annotate unknown-type calls.

### C) Static method calls (`StaticCall`)

Match when:

- `m` is not empty, and
- The static method name equals `m`, and
- The called class can be resolved and matches `c` (or subclass, if applicable)

Best-effort matching is acceptable for aliases/imports, but please document limitations.

### D) Method declarations (`ClassMethod`)

Match when:

- `m` is not empty, and
- The method name equals `m`, and
- The containing class is a subclass of `c` (or otherwise overrides that method in the PHP inheritance chain)

If this is hard to resolve perfectly, a best-effort approach is acceptable, but please document the limitation.

### E) Trait uses (`TraitUse`) for class/trait-level removals

Match when:

- `m === ''`, and
- the trait used in code resolves to `c` (or matches by short name as a best-effort fallback, if configured/necessary)

This is primarily for removed deprecated traits.

## Important constraints

### Configuration Injection

The rule must implement `Rector\Contract\Rector\ConfigurableRectorInterface` to receive the array of method signature changes. Do not hardcode the configuration array inside the rule class itself.

### Idempotency

The rule must be idempotent:

- Do not add duplicate TODO comments if the same TODO already exists.

### Existing docblocks / comments

If a `ClassMethod` already has a docblock:

- append a new `@TODO UPGRADE TASK - ...` line to the existing docblock (preferred), or
- otherwise preserve the existing docblock content and add the TODO without destroying it.

For non-docblock targets (method/static calls, trait uses), preserve existing comments and avoid duplicate insertions.

Please do not replace/remove existing docblocks.

## Exact comment text format

Use exactly this format:

```php
@TODO UPGRADE TASK - {ClassName}{optionalMethodPart}: {note}
```

Where:

- `{optionalMethodPart}` is `::{methodName}` when `m` is non-empty
- `{optionalMethodPart}` is omitted when `m` is empty (class/trait-level change)

Examples:

```php
@TODO UPGRADE TASK - SilverStripe\Core\Manifest\VersionProvider::getComposerLock: has been replaced by composer-runtime-api
```

```php
@TODO UPGRADE TASK - SilverStripe\Control\Middleware\URLSpecialsMiddleware\SessionEnvTypeSwitcher: Removed deprecated trait SilverStripe\Control\Middleware\URLSpecialsMiddleware\SessionEnvTypeSwitcher - removed without equivalent functionality to replace it
```

## What I want in the answer

Please provide:

- Full Rector rule class (`OtherMethod`)
- Any helper methods/classes needed
- Example Rector config registration
- Notes about required Rector services/packages (if any)
- Fixture tests (preferred) covering at least:

  - known typed instance method call (should annotate)
  - unknown typed instance method call + fallback safe = true (should annotate)
  - unknown typed instance method call + fallback safe = false (should not annotate)
  - static method call for "now static" / "no longer static" case (should annotate)
  - overriding class method in subclass (should annotate)
  - trait use for removed trait with `m === ''` (should annotate)
  - already annotated code (should not duplicate)
  - class method with existing docblock (should append TODO, not replace)

## Environment / assumptions

- I am using the latest Rector version
- I can install additional composer packages if needed

## Actual Data

Below is the data we have. I will add the `u` value later. This gives a good idea of what to expect.

```php
private const LIST =
[
    [
        'c' => 'DNADesign\\Elemental\\Controllers\\ElementalAreaController',
        'm' => 'apiSaveForm',
        'n' => 'send a POST request to elementForm/$ItemID instead',
    ],
    [
        'c' => 'SilverStripe\\Forms\\FileUploadReceiver',
        'm' => 'Value',
        'n' => 'Removed deprecated method SilverStripe\\Forms\\FileUploadReceiver::Value()',
    ],
    [
        'c' => 'SilverStripe\\Core\\Manifest\\VersionProvider',
        'm' => 'getComposerLock',
        'n' => 'has been replaced by composer-runtime-api',
    ],
    [
        'c' => 'SilverStripe\\Forms\\SearchableDropdownTrait',
        'm' => 'validate',
        'n' => 'removed in favour of the FormField::validate() method',
    ],
    ['c' => 'SiteTree', 'm' => 'getPermissionChecker', 'n' => 'Method SiteTree::getPermissionChecker() is no longer static'],
    ['c' => 'BuildTask', 'm' => 'getDescription', 'n' => 'Method BuildTask::getDescription() is now static'],
    ['c' => 'DBEnum', 'm' => 'flushCache', 'n' => 'Method DBEnum::flushCache() is no longer static'],
    [
        'c' => 'SilverStripe\\Control\\Middleware\\URLSpecialsMiddleware\\SessionEnvTypeSwitcher',
        'm' => '',
        'n' => 'Removed deprecated trait SilverStripe\\Control\\Middleware\\URLSpecialsMiddleware\\SessionEnvTypeSwitcher - removed without equivalent functionality to replace it',
    ],
    ['c' => 'MigrationTask', 'm' => 'down', 'n' => 'Method MigrationTask::down() is now abstract'],
    ['c' => 'MigrationTask', 'm' => 'up', 'n' => 'Method MigrationTask::up() is now abstract'],
];
```
