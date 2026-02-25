# Renamed Parameter

I want a custom Rector rule called `RenamedParameter` for the latest Rector version.

The purpose is to add a TODO upgrade comment when code calls or overrides methods whose parameter names changed, and the existing code likely needs manual review (especially for named arguments and overrides).

## Source data format

For each parameter rename, I have config like this:

```php
[
    'c' => 'BuildTask', // original class name
    'm' => 'run', // method name
    'n' => 'Renamed parameter $request in BuildTask::run() to $input', // explanation/note
    'u' => false, // true = method name is unique enough to match even when receiver type cannot be resolved
],
```

## Meaning of fields

c = class name where the parameter rename happened (may be fully-qualified or short class name, depending on how it is stored in my config)

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

This is a manual review marker. The rule does not need to automatically rename named arguments.

Before

```php
$service->run($request);
```

After

```php
/** @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Renamed parameter $request in BuildTask::run() to $input */
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
/** @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Renamed parameter $request in BuildTask::run() to $input */
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

### name space setup

The namespace is as follows:

Rector rule: `namespace Netwerkstatt\SilverstripeRector\Rector\Methods;`
Rector tests: `namespace Netwerkstatt\SilverstripeRector\Tests\Methods\XXX` where XXX is the name of the Rector Rule.

In the tests, I have set up the following folders / files:

- `config/configured_rule.php`
- `Fixture/fixture.php.inc`

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
@TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Renamed parameter $request in BuildTask::run() to $input
```

## What I want in the answer

Please provide:

- Full Rector rule class (`RenamedParameter`)
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

Now, we are talking about: `RenamedParameterChanges`

With the following data:

```php
private const LIST = [
    ['c' => 'Image_Backend', 'm' => 'paddedResize', 'n' => 'Renamed parameter $backgroundColor in Image_Backend::paddedResize() to $backgroundColour'],
    ['c' => 'VirtualPage', 'm' => 'hasField', 'n' => 'Renamed parameter $field in VirtualPage::hasField() to $fieldName'],
    ['c' => 'SSViewer', 'm' => 'process', 'n' => 'Renamed parameter $arguments in SSViewer::process() to $overlay'],
    ['c' => 'DBField', 'm' => 'saveInto', 'n' => 'Renamed parameter $dataObject in DBField::saveInto() to $model'],
    ['c' => 'DateField', 'm' => 'internalToFrontend', 'n' => 'Renamed parameter $date in DateField::internalToFrontend() to $value'],
    ['c' => 'DatetimeField', 'm' => 'internalToFrontend', 'n' => 'Renamed parameter $datetime in DatetimeField::internalToFrontend() to $value'],
    ['c' => 'DataObjectInterface', 'm' => '__get', 'n' => 'Renamed parameter $fieldName in DataObjectInterface::__get() to $property'],
    ['c' => 'DataObject', 'm' => 'get', 'n' => 'Renamed parameter $join in DataObject::get() to $limit'],
    ['c' => 'MemcachedCacheFactory', 'm' => '__construct', 'n' => 'Renamed parameter $memcachedClient in MemcachedCacheFactory::__construct() to $logger'],
    ['c' => 'SSViewer', 'm' => '__construct', 'n' => 'Renamed parameter $parser in SSViewer::__construct() to $templateEngine'],
    ['c' => 'BuildTask', 'm' => 'run', 'n' => 'Renamed parameter $request in BuildTask::run() to $input'],
    ['c' => 'i18nTextCollectorTask', 'm' => 'getIsMerge', 'n' => 'Renamed parameter $request in i18nTextCollectorTask::getIsMerge() to $input'],
    ['c' => 'TimeField', 'm' => 'internalToFrontend', 'n' => 'Renamed parameter $time in TimeField::internalToFrontend() to $value'],
    ['c' => 'Subsite', 'm' => 'get_from_all_subsites', 'n' => 'Renamed parameter $join in Subsite::get_from_all_subsites() to $limit'],
    ['c' => 'Versioned', 'm' => 'get_by_stage', 'n' => 'Renamed parameter $join in Versioned::get_by_stage() to $limit'],
    ['c' => 'HistoryViewerController', 'm' => 'getRecordVersion', 'n' => 'Renamed parameter $recordClass in HistoryViewerController::getRecordVersion() to $dataClass'],
];
```
