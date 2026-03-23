
# Obsolete Method

I want a custom Rector rule called `ObsoleteMethod` for the latest Rector version.

The purpose is to add a TODO upgrade comment when code still references or overrides methods that were removed from a known class.

## Source data format

For each removed method, I have config like this:

```php
[
    'c' => 'DNADesign\\Elemental\\Controllers\\ElementalAreaController', // original class name
    'm' => 'formAction', // removed method name
    'n' => 'removed without equivalent functionality to replace it', // explanation/note
    'u' => false, // true = method name is unique enough to match even when receiver type cannot be resolved
],
```

## Meaning of fields

c = fully-qualified class name where the removed method originally existed

m = removed method name

n = human-readable upgrade note

u = whether it is safe to add the TODO when the class/type cannot be determined (because the method name is unique enough and unlikely to be a false positive)

## What the Rector rule should do

The rule must detect at least:

- instance method calls (including nullsafe calls)
- class method declarations that override changed parent methods

Implementation detail: the Rector rule may inspect whatever AST node types are appropriate to achieve this.

## Transformation required (method calls)

If code calls a removed method on an instance of the configured class (or subclass), add a TODO doc comment immediately before the call.

Before

```php
$controller->formAction();
```

After

```php
/** @TODO SSU RECTOR UPGRADE TASK - DNADesign\Elemental\Controllers\ElementalAreaController::formAction: removed without equivalent functionality to replace it */
$controller->formAction();
```

## Transformation required (method overrides)

If a class method overrides a removed method from the configured class (or subclass relationship applies), add a TODO doc comment immediately before the method declaration.

Before

```php
function formAction()
{
    // ...
}
```

After

```php
/** @TODO SSU RECTOR UPGRADE TASK - DNADesign\Elemental\Controllers\ElementalAreaController::formAction: removed without equivalent functionality to replace it */
function formAction()
{
    // ...
}
```

## Matching rules

A) Method calls (MethodCall, NullsafeMethodCall)

Match when:

The method name equals m, and

The receiver object type can be resolved and is c or a subclass of c

B) Unknown receiver type fallback (method calls only)

If the receiver type cannot be resolved, only match when:

method name equals m, and

u === true

If u === false, do not annotate unknown-type calls.

C) Method declarations (ClassMethod)

Match when:

The method name equals m, and

The containing class is a subclass of c (or otherwise overrides that method in the PHP inheritance chain)

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

Do not add duplicate TODO comments if the same TODO already exists.

### Existing docblocks

If a ClassMethod already has a docblock:

append a new @TODO SSU RECTOR UPGRADE TASK - ... line to the existing docblock (preferred), or

otherwise preserve the existing docblock content and add the TODO without destroying it.

Please do not replace/remove existing docblocks.

## Exact comment text format

Use exactly this format:

```php
@TODO SSU RECTOR UPGRADE TASK - {ClassName}::{methodName}: {note}
```

Example:

```php
@TODO SSU RECTOR UPGRADE TASK - DNADesign\Elemental\Controllers\ElementalAreaController::formAction: removed without equivalent functionality to replace it
```

## What I want in the answer

Please provide:

Full Rector rule class (ObsoleteMethod)

Any helper methods/classes needed

Example Rector config registration

Notes about required Rector services/packages (if any)

Fixture tests (preferred) covering at least:

- known typed method call (should annotate)
- unknown typed method call + fallback safe = true (should annotate)
- unknown typed method call + fallback safe = false (should not annotate)
- overriding class method in subclass (should annotate)
- already annotated code (should not duplicate)

## Environment / assumptions

I am using the latest Rector version

I can install additional composer packages if needed

## Actual Data

Below is the data we have. I will add the 'u' value later. This gives you a good idea of what to expect.

```php


    public const NO_REPLACEMENT_AVAILABLE =
    [

        [
            'c' => 'DNADesign\\Elemental\\Controllers\\ElementalAreaController',
            'm' => 'formAction',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'DNADesign\\Elemental\\Controllers\\ElementalAreaController',
            'm' => 'removeNamespacesFromFields',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'DNADesign\\Elemental\\Models\\BaseElement',
            'm' => 'updateFromFormData',
            'n' => 'removed without equivalent functionality to replace it',
        ],

        [
            'c' => 'SilverStripe\\Admin\\LeftAndMain',
            'm' => 'Modals',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\Admin\\LeftAndMain',
            'm' => 'getSearchFilter',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\Admin\\ModalController',
            'm' => 'getController',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\Admin\\ModalController',
            'm' => 'getName',
            'n' => 'removed without equivalent functionality to replace it',
        ],

        [
            'c' => 'SilverStripe\\AssetAdmin\\Extensions\\RemoteFileModalExtension',
            'm' => 'getFormSchema',
            'n' => 'removed without equivalent functionality to replace it',
        ],

        [
            'c' => 'SilverStripe\\Assets\\Shortcodes\\ImageShortcodeProvider',
            'm' => 'createImageTag',
            'n' => 'removed without equivalent functionality to replace it',
        ],

        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
            'm' => 'PageListSidebar',
            'n' => 'removed without equivalent functionality',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
            'm' => 'getList',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
            'm' => 'getQueryFilter',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSSiteTreeFilter',
            'm' => '__construct',
            'n' => 'removed without a constructor to replace it',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSSiteTreeFilter',
            'm' => 'mapIDs',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSSiteTreeFilter',
            'm' => 'pagesIncluded',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSSiteTreeFilter',
            'm' => 'populateIDs',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\ContentController',
            'm' => 'deleteinstallfiles',
            'n' => 'removed without equivalent functionality',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\ContentController',
            'm' => 'successfullyinstalled',
            'n' => 'removed without equivalent functionality',
        ],

        [
            'c' => 'SilverStripe\\Control\\Controller',
            'm' => 'has_curr',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\Core\\BaseKernel',
            'm' => 'redirectToInstaller',
            'n' => 'removed without equivalent functionality',
        ],
        [
            'c' => 'SilverStripe\\Core\\Cache\\DefaultCacheFactory',
            'm' => 'isAPCUSupported',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\Core\\Manifest\\VersionProvider',
            'm' => 'getComposerLockPath',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\Dev\\Debug',
            'm' => 'require_developer_login',
            'n' => 'removed without equivalent functionality',
        ],
        [
            'c' => 'SilverStripe\\Dev\\DevelopmentAdmin',
            'm' => 'getRegisteredController',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\Forms\\GridField\\GridFieldDataColumns',
            'm' => 'getValueFromRelation',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\Forms\\GridField\\GridFieldFilterHeader',
            'm' => 'getThrowExceptionOnBadDataType',
            'n' => 'removed without equivalent functionality',
        ],
        [
            'c' => 'SilverStripe\\Forms\\GridField\\GridFieldFilterHeader',
            'm' => 'setThrowExceptionOnBadDataType',
            'n' => 'removed without equivalent functionality',
        ],
        [
            'c' => 'SilverStripe\\Forms\\GridField\\GridFieldPaginator',
            'm' => 'getThrowExceptionOnBadDataType',
            'n' => 'removed without equivalent functionality',
        ],
        [
            'c' => 'SilverStripe\\Forms\\GridField\\GridFieldPaginator',
            'm' => 'setThrowExceptionOnBadDataType',
            'n' => 'removed without equivalent functionality',
        ],
        [
            'c' => 'SilverStripe\\Forms\\GridField\\GridFieldSortableHeader',
            'm' => 'getThrowExceptionOnBadDataType',
            'n' => 'removed without equivalent functionality',
        ],
        [
            'c' => 'SilverStripe\\Forms\\GridField\\GridFieldSortableHeader',
            'm' => 'setThrowExceptionOnBadDataType',
            'n' => 'removed without equivalent functionality',
        ],
        [
            'c' => 'SilverStripe\\ORM\\DataObject',
            'm' => 'disable_subclass_access',
            'n' => 'removed without equivalent functionality',
        ],
        [
            'c' => 'SilverStripe\\ORM\\DataObject',
            'm' => 'enable_subclass_access',
            'n' => 'removed without equivalent functionality',
        ],
        [
            'c' => 'SilverStripe\\ORM\\FieldType\\DBInt',
            'm' => 'Times',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\Security\\RememberLoginHash',
            'm' => 'renew',
            'n' => 'removed without equivalent functionality',
        ],
        [
            'c' => 'SilverStripe\\View\\SSViewer',
            'm' => 'chooseTemplate',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\View\\SSViewer',
            'm' => 'exists',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\View\\SSViewer',
            'm' => 'getTemplateFileByType',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\View\\SSViewer',
            'm' => 'setTemplateFile',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\View\\SSViewer',
            'm' => 'templates',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\View\\SSViewer',
            'm' => 'topLevel',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\View\\ThemeResourceLoader',
            'm' => 'findTemplate',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\i18n\\Messages\\Symfony\\FlushInvalidatedResource',
            'm' => 'getResource',
            'n' => 'removed without equivalent functionality to replace it',
        ],

        [
            'c' => 'SilverStripe\\Subsites\\Controller\\SubsiteXHRController',
            'm' => 'canAccess',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\Subsites\\Extensions\\LeftAndMainSubsites',
            'm' => 'ListSubsites',
            'n' => 'removed without equivalent functionality to replace it',
        ],
        [
            'c' => 'SilverStripe\\Subsites\\Model\\Subsite',
            'm' => 'getMembersByPermission',
            'n' => 'removed without equivalent functionality',
        ],

        [
            'c' => 'SilverStripe\\Versioned\\Versioned',
            'm' => 'extendCanArchive',
            'n' => 'removed without equivalent functionality',
        ],
    ];

```
