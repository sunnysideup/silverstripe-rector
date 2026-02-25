# Removed Parameter

I want a custom Rector rule called `RemovedParameter` for the latest Rector version.

The purpose is to add a TODO upgrade comment when code calls or overrides methods where a parameter was removed, and the existing code likely needs manual review.

Of course, if there is a way to automatically fix this, even better. In some cases this may be possible (for example removing an extra trailing argument when safe), but the baseline requirement is to add TODO upgrade comments.

## Source data format

For each method change, I have config like this:

```php
[
    'c' => 'Vendor\\Package\\SomeClass', // original class name
    'm' => 'doThing', // method name
    'parameter' => '$oldArg', // removed parameter name
    'n' => 'Removed deprecated parameter $oldArg in SomeClass::doThing()', // explanation/note
    'u' => false, // true = method name is unique enough to match even when receiver type cannot be resolved
],
```

## Meaning of fields

c = class name where the method parameter was removed (may be fully-qualified or short class name, depending on how it is stored in my config)

m = method name

parameter = removed parameter name (for human-readable reporting and optional best-effort fixes)

n = human-readable upgrade note describing the removed parameter

u = whether it is safe to add the TODO when the class/type cannot be determined (because the method name is unique enough and unlikely to be a false positive)

## What the Rector rule should do

The rule must detect at least:

- instance method calls (including nullsafe calls)
- class method declarations that override changed parent methods

Implementation detail: the Rector rule may inspect whatever AST node types are appropriate to achieve this.

## Transformation required (method calls)

If code calls a changed method on an instance of the configured class (or subclass), add a TODO doc comment immediately before the call.

This is a manual review marker. The rule does not need to automatically remove arguments.

Before

```php
$controller->elementForm($request);
```

After

```php
/** @TODO SSU RECTOR UPGRADE TASK - DNADesign\Elemental\Controllers\ElementalAreaController::elementForm: Removed deprecated parameter $request in ElementalAreaController::elementForm() */
$controller->elementForm($request);
```

### Why method calls need review

Even if the call still compiles in some cases (for example via variadics or loose forwarding), removed parameters often mean the call site should pass fewer arguments or use a different API. This rule should flag such call sites for manual review.

## Transformation required (method overrides)

If a class method overrides a changed method from the configured class (or subclass relationship applies), add a TODO doc comment immediately before the method declaration.

Before

```php
function validate($validator)
{
    // ...
}
```

After

```php
/** @TODO SSU RECTOR UPGRADE TASK - SilverStripe\Forms\FormField::validate: Removed deprecated parameter $validator in FormField::validate() */
function validate($validator)
{
    // ...
}
```

### Why overrides need review

A removed parameter in a parent method can make subclass signatures incompatible or semantically outdated. This rule should flag overrides for manual review.

## Optional enhancement (best-effort only)

If feasible, the rule may attempt a conservative auto-fix in very limited cases, for example:

- an overriding `ClassMethod` clearly includes the removed parameter and it is unused in the method body (or can be safely removed)
- a method call clearly passes an extra trailing argument corresponding to the removed parameter and removing it is unambiguous

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
@TODO SSU RECTOR UPGRADE TASK - DNADesign\Elemental\Controllers\ElementalAreaController::elementForm: Removed deprecated parameter $request in ElementalAreaController::elementForm()
```

## What I want in the answer

Please provide:

- Full Rector rule class (`RemovedParameter`)
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
private const LIST =
[
    [
        'c' => 'DNADesign\\Elemental\\Controllers\\ElementalAreaController',
        'm' => 'getElementForm',
        'parameter' => '$elementID',
        'n' => 'Removed deprecated parameter $elementID in ElementalAreaController::getElementForm()',
    ],
    [
        'c' => 'DNADesign\\Elemental\\Controllers\\ElementalAreaController',
        'm' => 'elementForm',
        'parameter' => '$request',
        'n' => 'Removed deprecated parameter $request in ElementalAreaController::elementForm()',
    ],
    [
        'c' => 'SilverStripe\\Assets\\Storage\\DBFile',
        'm' => 'validate',
        'parameter' => '$filename',
        'n' => 'Removed deprecated parameter $filename in DBFile::validate()',
    ],
    [
        'c' => 'SilverStripe\\Assets\\Storage\\DBFile',
        'm' => 'validate',
        'parameter' => '$result',
        'n' => 'Removed deprecated parameter $result in DBFile::validate()',
    ],
    [
        'c' => 'SilverStripe\\View\\SSViewer',
        'm' => 'process',
        'parameter' => '$inheritedScope',
        'n' => 'Removed deprecated parameter $inheritedScope in SSViewer::process()',
    ],
    [
        'c' => 'SilverStripe\\Control\\Session',
        'm' => 'requestContainsSessionId',
        'parameter' => '$request',
        'n' => 'Removed deprecated parameter $request in Session::requestContainsSessionId()',
    ],
    [
        'c' => 'SilverStripe\\Forms\\FormField',
        'm' => 'validate',
        'parameter' => '$validator',
        'n' => 'Removed deprecated parameter $validator in FormField::validate()',
    ],
    [
        'c' => 'SilverStripe\\Versioned\\Versioned',
        'm' => 'Versions',
        'parameter' => '$having',
        'n' => 'Removed deprecated parameter $having in Versioned::Versions()',
    ],
];
```
