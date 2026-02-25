# Changed Default Parameter Value

I want a custom Rector rule called `ChangedDefaultParameterValue` for the latest Rector version.

The purpose is to add a TODO upgrade comment when code calls or overrides methods where the default value of an existing parameter changed, and the existing code likely needs manual review.

Of course, if there is a way to automatically fix this, even better. However, that seems highly unlikely, so the baseline requirement is to add TODO upgrade comments.

## Source data format

For each method change, I have config like this:

```php
[
    'c' => 'Vendor\\Package\\SomeClass', // original class name
    'm' => 'doThing', // method name
    'n' => 'Changed default value of parameter $mode in SomeClass::doThing() from null to \'strict\'', // explanation/note
    'u' => false, // true = method name is unique enough to match even when receiver type cannot be resolved
],
```

## Meaning of fields

c = class name where the method signature default value changed (may be fully-qualified or short class name, depending on how it is stored in my config)

m = method name

n = human-readable upgrade note describing the default value change

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
$service->doThing($value);
```

After

```php
/** @TODO SSU RECTOR UPGRADE TASK - SomeClass::doThing: Changed default value of parameter $mode in SomeClass::doThing() from null to 'strict' */
$service->doThing($value);
```

### Why method calls need review

Even if the call still compiles, changed default parameter values can alter behavior when the affected argument is omitted. This rule should flag such call sites for manual review.

## Transformation required (method overrides)

If a class method overrides a changed method from the configured class (or subclass relationship applies), add a TODO doc comment immediately before the method declaration.

Before

```php
function doThing($value, $mode = null)
{
    // ...
}
```

After

```php
/** @TODO SSU RECTOR UPGRADE TASK - SomeClass::doThing: Changed default value of parameter $mode in SomeClass::doThing() from null to 'strict' */
function doThing($value, $mode = null)
{
    // ...
}
```

### Why overrides need review

A changed default value in a parent method can create behavior mismatch if subclasses keep the old default. This rule should flag overrides for manual review.

## Optional enhancement (best-effort only)

If feasible, the rule may attempt a conservative auto-fix in very limited cases, for example:

- an overriding `ClassMethod` clearly defines the same parameter name/position and the old default literal is visible in source
- the intended new default literal is explicitly included in structured config (not currently required by this spec)

If auto-fix is implemented:

- it must be conservative and safe
- it must preserve formatting/comments as much as possible
- it must still be idempotent
- it should still add a TODO when confidence is low

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

### C) Method declarations (overrides)

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
@TODO SSU RECTOR UPGRADE TASK - SomeClass::doThing: Changed default value of parameter $mode in SomeClass::doThing() from null to 'strict'
```

## What I want in the answer

Please provide:

- Full Rector rule class (`ChangedDefaultParameterValue`)
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

Below is the data we have. I will add the `u` value later. This gives a good idea of what to expect.

```php

private const LIST = [
    ['c' => 'LeftAndMain', 'm' => 'jsonSuccess', 'n' => 'Changed default value for parameter $data in LeftAndMain::jsonSuccess() from [] to null'],
    ['c' => 'LeftAndMain', 'm' => 'jsonError', 'n' => 'Changed default value for parameter $errorMessage in LeftAndMain::jsonError() from null to \'\''],

    ['c' => 'AssetFormFactory', 'm' => 'getFormActions', 'n' => 'Changed default value for parameter $controller in AssetFormFactory::getFormActions() from null to none'],
    ['c' => 'AssetFormFactory', 'm' => 'getFormFields', 'n' => 'Changed default value for parameter $controller in AssetFormFactory::getFormFields() from null to none'],
    ['c' => 'AssetFormFactory', 'm' => 'getValidator', 'n' => 'Changed default value for parameter $controller in AssetFormFactory::getValidator() from null to none'],
    ['c' => 'FileSearchFormFactory', 'm' => 'getFormFields', 'n' => 'Changed default value for parameter $controller in FileSearchFormFactory::getFormFields() from null to none'],

    ['c' => 'BlogPostFilter', 'm' => 'augmentLoadLazyFields', 'n' => 'Changed default value for parameter $dataQuery in BlogPostFilter::augmentLoadLazyFields() from null to none'],

    ['c' => 'SSViewer', 'm' => 'process', 'n' => 'Changed default value for parameter $arguments in SSViewer::process() from null to []'],
    ['c' => 'CookieJar', 'm' => 'outputCookie', 'n' => 'Changed default value for parameter $expiry in CookieJar::outputCookie() from 90 to none'],
    ['c' => 'Form', 'm' => 'loadDataFrom', 'n' => 'Changed default value for parameter $fieldList in Form::loadDataFrom() from null to []'],
    ['c' => 'DataObject', 'm' => 'get', 'n' => 'Changed default value for parameter $join in DataObject::get() from \'\' to null'],
    ['c' => 'DB', 'm' => 'connect', 'n' => 'Changed default value for parameter $label in DB::connect() from \'default\' to DB::CONN_DYNAMIC'],
    ['c' => 'SearchContext', 'm' => 'getQuery', 'n' => 'Changed default value for parameter $limit in SearchContext::getQuery() from false to null'],
    ['c' => 'DB', 'm' => 'build_sql', 'n' => 'Changed default value for parameter $name in DB::build_sql() from \'default\' to DB::CONN_DYNAMIC'],
    ['c' => 'DB', 'm' => 'getConfig', 'n' => 'Changed default value for parameter $name in DB::getConfig() from \'default\' to DB::CONN_PRIMARY'],
    ['c' => 'DB', 'm' => 'get_conn', 'n' => 'Changed default value for parameter $name in DB::get_conn() from \'default\' to DB::CONN_DYNAMIC'],
    ['c' => 'DB', 'm' => 'get_connector', 'n' => 'Changed default value for parameter $name in DB::get_connector() from \'default\' to DB::CONN_DYNAMIC'],
    ['c' => 'DB', 'm' => 'get_schema', 'n' => 'Changed default value for parameter $name in DB::get_schema() from \'default\' to DB::CONN_DYNAMIC'],
    ['c' => 'DB', 'm' => 'setConfig', 'n' => 'Changed default value for parameter $name in DB::setConfig() from \'default\' to DB::CONN_PRIMARY'],
    ['c' => 'DB', 'm' => 'set_conn', 'n' => 'Changed default value for parameter $name in DB::set_conn() from \'default\' to none'],
    ['c' => 'TempDatabase', 'm' => '__construct', 'n' => 'Changed default value for parameter $name in TempDatabase::__construct() from \'default\' to DB::CONN_PRIMARY'],
    ['c' => 'DBField', 'm' => 'scaffoldFormField', 'n' => 'Changed default value for parameter $params in DBField::scaffoldFormField() from null to []'],
    ['c' => 'SSViewer', 'm' => 'add_themes', 'n' => 'Changed default value for parameter $themes in SSViewer::add_themes() from [] to none'],
    ['c' => 'SSViewer', 'm' => 'set_themes', 'n' => 'Changed default value for parameter $themes in SSViewer::set_themes() from [] to none'],

    ['c' => 'Versioned', 'm' => 'augmentLoadLazyFields', 'n' => 'Changed default value for parameter $dataQuery in Versioned::augmentLoad_lazy_fields() from null to none'],
    ['c' => 'Versioned', 'm' => 'get_by_stage', 'n' => 'Changed default value for parameter $join in Versioned::get_by_stage() from \'\' to null'],

    ['c' => 'DataObjectVersionFormFactory', 'm' => 'getFormActions', 'n' => 'Changed default value for parameter $controller in DataObjectVersionFormFactory::getFormActions() from null to none'],
    ['c' => 'DataObjectVersionFormFactory', 'm' => 'getFormFields', 'n' => 'Changed default value for parameter $controller in DataObjectVersionFormFactory::getFormFields() from null to none'],


];

```
