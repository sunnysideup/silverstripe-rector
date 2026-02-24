# Add New Parameter

I want a custom Rector rule called `AddNewParameter` for the latest Rector version.

The purpose is to add a TODO upgrade comment when code calls or overrides methods whose signature changed by adding a new parameter, and the existing code likely needs manual review.

## Source data format

For each method signature change, I have config like this:

```php
[
    'c' => 'DNADesign\\Elemental\\Controllers\\ElementalAreaController', // original class name
    'm' => 'formAction', // method name
    'n' => 'Added new parameter $output in BuildTask::run()', // explanation/note
    'u' => false, // true = method name is unique enough to match even when receiver type cannot be resolved
],
```

## Meaning of fields

c = class name where the method signature changed (may be fully-qualified or short class name, depending on how it is stored in my config)

m = method name

n = human-readable upgrade note

u = whether it is safe to add the TODO when the class/type cannot be determined (because the method name is unique enough and unlikely to be a false positive)

## What the Rector rule should do

The rule must detect at least:

- instance method calls (including nullsafe calls)
- class method declarations that override changed parent methods

Implementation detail: the Rector rule may inspect whatever AST node types are appropriate to achieve this.

## Transformation required (method calls)

If code calls a changed method on an instance of the configured class (or subclass), add a TODO doc comment immediately before the call.

This is a manual review marker. The rule does not need to automatically add arguments.

Before

```php
$service->run($request);
```

After

```php
/** @TODO UPGRADE TASK - BuildTask::run: Added new parameter $output in BuildTask::run() */
$service->run($request);
```

## Transformation required (method overrides)

If a class method overrides a changed method from the configured class (or subclass relationship applies), add a TODO doc comment immediately before the method declaration.

Before

```php
function run($request)
{
    // ...
}
```

After

```php
/** @TODO UPGRADE TASK - BuildTask::run: Added new parameter $output in BuildTask::run() */
function run($request)
{
    // ...
}
```

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

### Configuration Injection

The rule must implement `Rector\Contract\Rector\ConfigurableRectorInterface` to receive the array of method signature changes. Do not hardcode the configuration array inside the rule class itself.

### Idempotency

The rule must be idempotent:

- Do not add duplicate TODO comments if the same TODO already exists.

### Existing docblocks

If a `ClassMethod` already has a docblock:

- append a new `@TODO UPGRADE TASK - ...` line to the existing docblock (preferred), or
- otherwise preserve the existing docblock content and add the TODO without destroying it.

Please do not replace/remove existing docblocks.

## Exact comment text format

Use exactly this format:

```php
@TODO UPGRADE TASK - {ClassName}::{methodName}: {note}
```

Example:

```php
@TODO UPGRADE TASK - BuildTask::run: Added new parameter $output in BuildTask::run()
```

## What I want in the answer

Please provide:

- Full Rector rule class (`AddNewParameter`)
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

Below is the data we have. I will add the 'u' value later. This gives you a good idea of what to expect.

```php
private const LIST = [
    ['c' => 'ReorderElements', 'm' => '__construct', 'n' => 'Added new parameter $elementIsNew in ReorderElements::__construct()'],

    ['c' => 'Image_Backend', 'm' => 'crop', 'n' => 'Added new parameter $backgroundColour in Image_Backend::crop()'],
    ['c' => 'Image_Backend', 'm' => 'crop', 'n' => 'Added new parameter $position in Image_Backend::crop()'],
    ['c' => 'Image_Backend', 'm' => 'croppedResize', 'n' => 'Added new parameter $position in Image_Backend::croppedResize()'],

    ['c' => 'CMSSiteTreeFilter', 'm' => 'getFilteredPages', 'n' => 'Added new parameter $list in CMSSiteTreeFilter::getFilteredPages()'],
    ['c' => 'VirtualPage', 'm' => 'castingHelper', 'n' => 'Added new parameter $useFallback in VirtualPage::castingHelper()'],

    ['c' => 'ChangePasswordHandler', 'm' => 'setSessionToken', 'n' => 'Added new parameter $alreadyEncrypted in ChangePasswordHandler::setSessionToken()'],
    ['c' => 'DataList', 'm' => 'excludeAny', 'n' => 'Added new parameter $args in DataList::excludeAny()'],
    ['c' => 'DataQuery', 'm' => 'conjunctiveGroup', 'n' => 'Added new parameter $clause in DataQuery::conjunctiveGroup()'],
    ['c' => 'DataQuery', 'm' => 'disjunctiveGroup', 'n' => 'Added new parameter $clause in DataQuery::disjunctiveGroup()'],
    ['c' => 'MoneyField', 'm' => 'buildCurrencyField', 'n' => 'Added new parameter $forceTextField in MoneyField::buildCurrencyField()'],
    ['c' => 'DBDate', 'm' => 'Format', 'n' => 'Added new parameter $locale in DBDate::Format()'],
    ['c' => 'BuildTask', 'm' => 'run', 'n' => 'Added new parameter $output in BuildTask::run()'],
    ['c' => 'Convert', 'm' => 'linkIfMatch', 'n' => 'Added new parameter $protocols in Convert::linkIfMatch()'],
    ['c' => 'DateField', 'm' => 'tidyInternal', 'n' => 'Added new parameter $returnNullOnFailure in DateField::tidyInternal()'],
    ['c' => 'DatetimeField', 'm' => 'tidyInternal', 'n' => 'Added new parameter $returnNullOnFailure in DatetimeField::tidyInternal()'],
    ['c' => 'TimeField', 'm' => 'tidyInternal', 'n' => 'Added new parameter $returnNullOnFailure in TimeField::tidyInternal()'],
    ['c' => 'Cookie', 'm' => 'force_expiry', 'n' => 'Added new parameter $sameSite in Cookie::force_expiry()'],
    ['c' => 'Cookie', 'm' => 'set', 'n' => 'Added new parameter $sameSite in Cookie::set()'],
    ['c' => 'CookieJar', 'm' => 'outputCookie', 'n' => 'Added new parameter $sameSite in CookieJar::outputCookie()'],
    ['c' => 'Cookie_Backend', 'm' => 'forceExpiry', 'n' => 'Added new parameter $sameSite in Cookie_Backend::forceExpiry()'],
    ['c' => 'Cookie_Backend', 'm' => 'set', 'n' => 'Added new parameter $sameSite in Cookie_Backend::set()'],
    ['c' => 'DataObject', 'm' => 'preWrite', 'n' => 'Added new parameter $skipValidation in DataObject::preWrite()'],
    ['c' => 'DataObject', 'm' => 'validateWrite', 'n' => 'Added new parameter $skipValidation in DataObject::validateWrite()'],

];

```
