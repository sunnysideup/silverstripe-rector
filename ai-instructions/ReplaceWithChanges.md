# Replace With

I want a custom Rector rule called `ReplaceWith` for the latest Rector version.

The purpose is to add a TODO upgrade comment when code calls or overrides methods that have been replaced with another method, API, class, or workflow, and the existing code likely needs manual review.

If there is a safe and reliable way to automatically replace usage, even better. However, the baseline requirement is to add TODO upgrade comments.

## Source data format

For each replacement change, I have config like this:

```php
[
    'c' => 'Vendor\\Package\\SomeClass', // original class name
    'm' => 'oldMethodName', // method name
    'n' => 'replaced with NewApi::newMethod()', // explanation/note
    'u' => false, // true = method name is unique enough to match even when receiver type cannot be resolved
],
```

## Meaning of fields

c = class name where the method replacement applies (may be fully-qualified or short class name, depending on how it is stored in my config)

m = old method name (the method name to match in existing code)

n = human-readable upgrade note describing the replacement target (for example `replaced with SSTemplateEngine::renderString()`)

u = whether it is safe to add the TODO when the class/type cannot be determined (because the method name is unique enough and unlikely to be a false positive)

## What the Rector rule should do

The rule must detect at least:

- instance method calls (including nullsafe calls)
- class method declarations that override/implement the replaced method in subclasses/traits/extensions

Implementation detail: the Rector rule may inspect whatever AST node types are appropriate to achieve this.

## Transformation required (method calls)

If code calls a replaced method on an instance of the configured class (or subclass), add a TODO doc comment immediately before the call.

This is a manual review marker. The rule does not need to automatically replace the call.

Before

```php
$viewer->fromString($template, $data);
```

After

```php
/** @TODO UPGRADE TASK - SilverStripe\View\SSViewer::fromString: replaced with SSTemplateEngine::renderString() */
$viewer->fromString($template, $data);
```

## Transformation required (method overrides)

If a class method overrides or implements a replaced method from the configured class (or subclass relationship applies), add a TODO doc comment immediately before the method declaration.

Before

```php
function getCMSValidator()
{
    // ...
}
```

After

```php
/** @TODO UPGRADE TASK - SilverStripe\UserForms\UserForm::getCMSValidator: replaced with getCMSCompositeValidator() */
function getCMSValidator()
{
    // ...
}
```

## Why overrides need review

Replacement changes often require method renaming, changed call flow, or different extension hooks. This rule should flag overrides/implementations for manual review.

## Optional enhancement (best-effort only)

If feasible, the rule may attempt a conservative auto-fix in limited cases where the replacement is unambiguous and directly mappable, for example:

- `replaced with SomeClass::someMethod()`
- `replaced with someMethod()`

If auto-fix is implemented:

- it must be conservative and safe
- it must preserve formatting/comments as much as possible
- it must still be idempotent
- it should still add a TODO when confidence is low or when replacement semantics are not guaranteed equivalent
- it must not attempt automatic replacement for notes that describe broader workflows or non-method replacements (for example `replaced with a SearchContext subclass` or `replaced with functionality in silverstripe/admin`)

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
@TODO UPGRADE TASK - SilverStripe\View\SSViewer::fromString: replaced with SSTemplateEngine::renderString()
```

## What I want in the answer

Please provide:

- Full Rector rule class (`ReplaceWith`)
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

- safe direct method replacement note (annotate and/or rename call if implemented)
- safe direct method declaration rename (if implemented)
- non-method replacement note (annotate only, no auto-fix)

## Environment / assumptions

- I am using the latest Rector version
- I can install additional composer packages if needed

## Actual Data

Below is the data we have. I will add the `u` value later. This gives a good idea of what to expect.

```php
private const LIST =
[
    [
        'c' => 'DNADesign\\Elemental\\Models\\BaseElement',
        'm' => 'getGraphQLTypeName',
        'n' => 'replaced with getTypeName()',
    ],

    [
        'c' => 'SilverStripe\\Admin\\CMSEditLinkExtension',
        'm' => 'CMSEditLink',
        'n' => 'replaced with DataObject::getCMSEditLink() and updateCMSEditLink()',
    ],
    [
        'c' => 'SilverStripe\\Admin\\LeftAndMain',
        'm' => 'methodSchema',
        'n' => 'replaced with FormSchemaController::schema()',
    ],
    [
        'c' => 'SilverStripe\\Admin\\ModalController',
        'm' => 'EditorEmailLink',
        'n' => 'replaced with linkModalForm()',
    ],
    [
        'c' => 'SilverStripe\\Admin\\ModalController',
        'm' => 'EditorExternalLink',
        'n' => 'replaced with linkModalForm()',
    ],

    [
        'c' => 'SilverStripe\\AssetAdmin\\Extensions\\RemoteFileModalExtension',
        'm' => 'getSchemaResponse',
        'n' => 'replaced with $this->getOwner()->getSchemaResponse() instead',
    ],

    [
        'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
        'm' => 'getSearchContext',
        'n' => 'replaced with SiteTree::getDefaultSearchContext()',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
        'm' => 'getSearchFieldSchema',
        'n' => 'replaced with SearchContextForm::getSchemaData()',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Controllers\\CMSSiteTreeFilter',
        'm' => 'applyDefaultFilters',
        'n' => 'replaced with a SearchContext subclass',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Model\\SiteTree',
        'm' => 'creatableChildPages',
        'n' => 'replaced with CMSMain::getCreatableSubClasses()',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Model\\SiteTree',
        'm' => 'generateChildrenCacheKey',
        'n' => 'replaced with CMSMain::generateChildrenCacheKey()',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Model\\SiteTree',
        'm' => 'getCreatableChildrenCache',
        'n' => 'replaced with CMSMain::getCreatableChildrenCache()',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Model\\SiteTree',
        'm' => 'getIconClass',
        'n' => 'replaced with CMSMain::getRecordIconCssClass()',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Model\\SiteTree',
        'm' => 'getPageIconURL',
        'n' => 'replaced with CMSMain::getRecordIconUrl()',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Model\\SiteTree',
        'm' => 'page_type_classes',
        'n' => 'replaced with updateAllowedSubClasses()',
    ],
    [
        'c' => 'SilverStripe\\CMS\\Model\\SiteTree',
        'm' => 'setCreatableChildrenCache',
        'n' => 'replaced with CMSMain::setCreatableChildrenCache()',
    ],

    [
        'c' => 'SilverStripe\\Dev\\DevelopmentAdmin',
        'm' => 'buildDefaults',
        'n' => 'replaced with SilverStripe\\Dev\\Commands\\DbDefaults',
    ],
    [
        'c' => 'SilverStripe\\Dev\\DevelopmentAdmin',
        'm' => 'generatesecuretoken',
        'n' => 'replaced with SilverStripe\\Dev\\Commands\\GenerateSecureToken',
    ],
    [
        'c' => 'SilverStripe\\Dev\\DevelopmentAdmin',
        'm' => 'runRegisteredController',
        'n' => 'replaced with runRegisteredAction()',
    ],

    [
        'c' => 'SilverStripe\\Dev\\Tasks\\CleanupTestDatabasesTask',
        'm' => 'canView',
        'n' => 'replaced with canRunInBrowser()',
    ],

    [
        'c' => 'SilverStripe\\Forms\\FormField',
        'm' => 'Value',
        'n' => 'replaced by getFormattedValue() and getValue()',
    ],

    [
        'c' => 'SilverStripe\\Forms\\HTMLEditor\\HTMLEditorSanitiser',
        'm' => 'addValidElements',
        'n' => 'replaced with HTMLEditorRuleSet',
    ],
    [
        'c' => 'SilverStripe\\Forms\\HTMLEditor\\HTMLEditorSanitiser',
        'm' => 'attributeMatchesRule',
        'n' => 'replaced with HTMLEditorElementRule::isAttributeAllowed()',
    ],
    [
        'c' => 'SilverStripe\\Forms\\HTMLEditor\\HTMLEditorSanitiser',
        'm' => 'elementMatchesRule',
        'n' => 'replaced with HTMLEditorRuleSet::isElementAllowed()',
    ],
    [
        'c' => 'SilverStripe\\Forms\\HTMLEditor\\HTMLEditorSanitiser',
        'm' => 'getRuleForAttribute',
        'n' => 'replaced with logic in HTMLEditorElementRule',
    ],
    [
        'c' => 'SilverStripe\\Forms\\HTMLEditor\\HTMLEditorSanitiser',
        'm' => 'getRuleForElement',
        'n' => 'replaced with HTMLEditorRuleSet::getRuleForElement()',
    ],
    [
        'c' => 'SilverStripe\\Forms\\HTMLEditor\\HTMLEditorSanitiser',
        'm' => 'patternToRegex',
        'n' => 'replaced with HTMLEditorRuleSet::patternToRegex()',
    ],

    [
        'c' => 'SilverStripe\\Forms\\HTMLReadonlyField',
        'm' => 'ValueEntities',
        'n' => 'replaced by getFormattedValueEntities()',
    ],
    [
        'c' => 'SilverStripe\\Forms\\TextareaField',
        'm' => 'ValueEntities',
        'n' => 'replaced by getFormattedValueEntities()',
    ],

    [
        'c' => 'SilverStripe\\Forms\\GridField\\GridFieldFilterHeader',
        'm' => 'getSearchFieldSchema',
        'n' => 'replaced with SearchContextForm::getSchemaData()',
    ],
    [
        'c' => 'SilverStripe\\Forms\\GridField\\GridFieldFilterHeader',
        'm' => 'getSearchFormSchema',
        'n' => 'replaced with FormRequestHandler::getSchema()',
    ],

    [
        'c' => 'SilverStripe\\ORM\\PolymorphicHasManyList',
        'm' => 'setForeignRelation',
        'n' => 'replaced with a parameter in the constructor',
    ],

    [
        'c' => 'SilverStripe\\View\\SSViewer',
        'm' => 'execute_string',
        'n' => 'replaced with SSTemplateEngine::renderString()',
    ],
    [
        'c' => 'SilverStripe\\View\\SSViewer',
        'm' => 'execute_template',
        'n' => 'replaced with SSTemplateEngine::execute_template()',
    ],
    [
        'c' => 'SilverStripe\\View\\SSViewer',
        'm' => 'flush_cacheblock_cache',
        'n' => 'replaced with SSTemplateEngine::flushCacheBlockCache()',
    ],
    [
        'c' => 'SilverStripe\\View\\SSViewer',
        'm' => 'flush_template_cache',
        'n' => 'replaced with SSTemplateEngine::flushTemplateCache()',
    ],
    [
        'c' => 'SilverStripe\\View\\SSViewer',
        'm' => 'fromString',
        'n' => 'replaced with SSTemplateEngine::renderString()',
    ],
    [
        'c' => 'SilverStripe\\View\\SSViewer',
        'm' => 'getParser',
        'n' => 'replaced with SSTemplateEngine::getParser()',
    ],
    [
        'c' => 'SilverStripe\\View\\SSViewer',
        'm' => 'getPartialCacheStore',
        'n' => 'replaced with SSTemplateEngine::getPartialCacheStore()',
    ],
    [
        'c' => 'SilverStripe\\View\\SSViewer',
        'm' => 'getSubtemplateFor',
        'n' => 'replaced with SSTemplateEngine::getSubtemplateFor()',
    ],
    [
        'c' => 'SilverStripe\\View\\SSViewer',
        'm' => 'hasTemplate',
        'n' => 'replaced with SSTemplateEngine::hasTemplate()',
    ],
    [
        'c' => 'SilverStripe\\View\\SSViewer',
        'm' => 'includeGeneratedTemplate',
        'n' => 'replaced with SSTemplateEngine::includeGeneratedTemplate()',
    ],
    [
        'c' => 'SilverStripe\\View\\SSViewer',
        'm' => 'parseTemplateContent',
        'n' => 'replaced with SSTemplateEngine::parseTemplateContent()',
    ],
    [
        'c' => 'SilverStripe\\View\\SSViewer',
        'm' => 'setParser',
        'n' => 'replaced with SSTemplateEngine::setParser()',
    ],
    [
        'c' => 'SilverStripe\\View\\SSViewer',
        'm' => 'setPartialCacheStore',
        'n' => 'replaced with SSTemplateEngine::setPartialCacheStore()',
    ],
    [
        'c' => 'SilverStripe\\View\\SSViewer',
        'm' => 'setTemplate',
        'n' => 'replaced with SSTemplateEngine::setTemplate()',
    ],

    [
        'c' => 'SilverStripe\\LinkField\\Tasks\\MigrationTaskTrait',
        'm' => 'run',
        'n' => 'replaced with execute()',
    ],

    [
        'c' => 'SilverStripe\\SiteConfig\\SiteConfigLeftAndMain',
        'm' => 'save_siteconfig',
        'n' => 'replaced with save()',
    ],

    [
        'c' => 'SilverStripe\\Versioned\\VersionedGridFieldItemRequest',
        'm' => 'getRecordStatus',
        'n' => 'replaced with Versioned::updateStatusFlags()',
    ],

    [
        'c' => 'SilverStripe\\UserForms\\Model\\EditableFormField',
        'm' => 'getCMSValidator',
        'n' => 'replaced with getCMSCompositeValidator()',
    ],
    [
        'c' => 'SilverStripe\\UserForms\\UserForm',
        'm' => 'getCMSValidator',
        'n' => 'replaced with getCMSCompositeValidator()',
    ],

    [
        'c' => 'Symbiote\\AdvancedWorkflow\\Extensions\\WorkflowEmbargoExpiryExtension',
        'm' => 'getCMSValidator',
        'n' => 'replaced with updateCMSCompositeValidator()',
    ],

    [
        'c' => 'TractorCow\\Fluent\\Extension\\FluentGridFieldExtension',
        'm' => 'updateBadge',
        'n' => 'replaced with FluentExtension::updateStatusFlags()',
    ],
    [
        'c' => 'TractorCow\\Fluent\\Extension\\FluentLeftAndMainExtension',
        'm' => 'updateBreadcrumbs',
        'n' => 'replaced with functionality in silverstripe/admin',
    ],
];
```
