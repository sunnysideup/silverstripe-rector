# Return Type Changes

I want a custom Rector rule called `ReturnType` for the latest Rector version.

The purpose is to add a TODO upgrade comment when code calls or overrides methods whose **return type changed**, and the existing code likely needs manual review.

## Source data format

For each method return type change, I have config like this:

```php
[
    'c' => 'DNADesign\\Elemental\\Models\\BaseElement', // original class name
    'm' => 'forTemplate', // method name
    'n' => 'Changed return type for method BaseElement::forTemplate() from dynamic to string', // explanation/note
    'u' => false, // true = method name is unique enough to match even when receiver type cannot be resolved
],
```

## Meaning of fields

`c` = class name where the method signature/return type changed (may be fully-qualified or short class name, depending on how it is stored in my config)

`m` = method name

`n` = human-readable upgrade note

`u` = whether it is safe to add the TODO when the class/type cannot be determined (because the method name is unique enough and unlikely to be a false positive)

## What the Rector rule should do

The rule must detect at least:

- instance method calls (including nullsafe calls)
- class method declarations that override changed parent methods

Implementation detail: the Rector rule may inspect whatever AST node types are appropriate to achieve this.

## Transformation required (method calls)

If code calls a changed method on an instance of the configured class (or subclass), add a TODO doc comment immediately before the call.

This is a manual review marker. The rule does not need to automatically change the code.

Before

```php
$value = $item->forTemplate();
```

After

```php
/** @TODO SSU RECTOR UPGRADE TASK - BaseElement::forTemplate: Changed return type for method BaseElement::forTemplate() from dynamic to string */
$value = $item->forTemplate();
```

## Transformation required (method overrides)

If a class method overrides a changed method from the configured class (or subclass relationship applies), add a TODO doc comment immediately before the method declaration.

Before

```php
function forTemplate()
{
    // ...
}
```

After

```php
/** @TODO SSU RECTOR UPGRADE TASK - BaseElement::forTemplate: Changed return type for method BaseElement::forTemplate() from dynamic to string */
function forTemplate()
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
@TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Changed return type for method BuildTask::run() from dynamic to int
```

## What I want in the answer

Please provide:

- Full Rector rule class (`ReturnType`)
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
private const LIST = [
        ['c' => 'BaseElement', 'm' => 'Top', 'n' => 'Changed return type for method BaseElement::Top() from dynamic to Controller|null'],
        ['c' => 'BaseElement', 'm' => 'forTemplate', 'n' => 'Changed return type for method BaseElement::forTemplate() from dynamic to string'],
        ['c' => 'BaseElement', 'm' => 'isCMSPreview', 'n' => 'Changed return type for method BaseElement::isCMSPreview() from dynamic to bool'],
        ['c' => 'EditFormFactory', 'm' => 'namespaceFields', 'n' => 'Changed return type for method EditFormFactory::namespaceFields() from dynamic to void'],
        ['c' => 'ElementController', 'm' => 'forTemplate', 'n' => 'Changed return type for method ElementController::forTemplate() from dynamic to string'],
        ['c' => 'ElementalArea', 'm' => 'forTemplate', 'n' => 'Changed return type for method ElementalArea::forTemplate() from dynamic to string'],
        ['c' => 'ElementalAreaController', 'm' => 'elementForm', 'n' => 'Changed return type for method ElementalAreaController::elementForm() from dynamic to Form'],
        ['c' => 'ElementalAreaController', 'm' => 'getElementForm', 'n' => 'Changed return type for method ElementalAreaController::getElementForm() from dynamic to Form'],

        ['c' => 'AdminRootController', 'm' => 'add_rule_for_controller', 'n' => 'Changed return type for method AdminRootController::add_rule_for_controller() from dynamic to void'],
        ['c' => 'AdminRootController', 'm' => 'admin_url', 'n' => 'Changed return type for method AdminRootController::admin_url() from dynamic to string'],
        ['c' => 'AdminRootController', 'm' => 'get_admin_route', 'n' => 'Changed return type for method AdminRootController::get_admin_route() from dynamic to string'],
        ['c' => 'AdminRootController', 'm' => 'rules', 'n' => 'Changed return type for method AdminRootController::rules() from dynamic to array'],
        ['c' => 'LeftAndMain', 'm' => 'getClientConfig', 'n' => 'Changed return type for method LeftAndMain::getClientConfig() from dynamic to array'],
        ['c' => 'LeftAndMain', 'm' => 'getFormSchema', 'n' => 'Changed return type for method LeftAndMain::getFormSchema() from dynamic to FormSchema'],
        ['c' => 'LeftAndMain', 'm' => 'getRecord', 'n' => 'Changed return type for method LeftAndMain::getRecord() from dynamic to DataObject|null'],
        ['c' => 'LeftAndMain', 'm' => 'getRequiredPermissions', 'n' => 'Changed return type for method LeftAndMain::getRequiredPermissions() from dynamic to array|string|false'],
        ['c' => 'LeftAndMain', 'm' => 'getSchemaRequested', 'n' => 'Changed return type for method LeftAndMain::getSchemaRequested() from dynamic to bool'],
        ['c' => 'LeftAndMain', 'm' => 'getTemplatesWithSuffix', 'n' => 'Changed return type for method LeftAndMain::getTemplatesWithSuffix() from dynamic to array'],
        ['c' => 'LeftAndMain', 'm' => 'jsonError', 'n' => 'Changed return type for method LeftAndMain::jsonError() from dynamic to void'],
        ['c' => 'LeftAndMain', 'm' => 'setFormSchema', 'n' => 'Changed return type for method LeftAndMain::setFormSchema() from dynamic to FormSchemaController'],
        ['c' => 'ModelAdmin', 'm' => 'getModelClass', 'n' => 'Changed return type for method ModelAdmin::getModelClass() from dynamic to string'],

        ['c' => 'AssetAdmin', 'm' => 'apiCreateFile', 'n' => 'Changed return type for method AssetAdmin::apiCreateFile() from dynamic to HTTPResponse'],
        ['c' => 'AssetAdmin', 'm' => 'apiHistory', 'n' => 'Changed return type for method AssetAdmin::apiHistory() from dynamic to HTTPResponse'],
        ['c' => 'AssetAdmin', 'm' => 'apiUploadFile', 'n' => 'Changed return type for method AssetAdmin::apiUploadFile() from dynamic to HTTPResponse'],
        ['c' => 'AssetAdmin', 'm' => 'getThumbnailGenerator', 'n' => 'Changed return type for method AssetAdmin::getThumbnailGenerator() from dynamic to ThumbnailGenerator'],
        ['c' => 'AssetAdmin', 'm' => 'setThumbnailGenerator', 'n' => 'Changed return type for method AssetAdmin::setThumbnailGenerator() from dynamic to AssetAdminOpen'],

        ['c' => 'DBFile', 'm' => 'AbsoluteLink', 'n' => 'Changed return type for method DBFile::AbsoluteLink() from dynamic to string'],
        ['c' => 'DBFile', 'm' => 'Link', 'n' => 'Changed return type for method DBFile::Link() from dynamic to string'],
        ['c' => 'DBFile', 'm' => 'assertFilenameValid', 'n' => 'Changed return type for method DBFile::assertFilenameValid() from dynamic to void'],
        ['c' => 'DBFile', 'm' => 'getAllowedCategories', 'n' => 'Changed return type for method DBFile::getAllowedCategories() from dynamic to array'],
        ['c' => 'DBFile', 'm' => 'getAllowedExtensions', 'n' => 'Changed return type for method DBFile::getAllowedExtensions() from dynamic to array'],
        ['c' => 'DBFile', 'm' => 'getBasename', 'n' => 'Changed return type for method DBFile::getBasename() from dynamic to string'],
        ['c' => 'DBFile', 'm' => 'getExtension', 'n' => 'Changed return type for method DBFile::getExtension() from dynamic to string'],
        ['c' => 'DBFile', 'm' => 'getFrontendTemplate', 'n' => 'Changed return type for method DBFile::getFrontendTemplate() from dynamic to string'],
        ['c' => 'DBFile', 'm' => 'getSize', 'n' => 'Changed return type for method DBFile::getSize() from dynamic to string|false'],
        ['c' => 'DBFile', 'm' => 'getSourceURL', 'n' => 'Changed return type for method DBFile::getSourceURL() from dynamic to string'],
        ['c' => 'DBFile', 'm' => 'getStore', 'n' => 'Changed return type for method DBFile::getStore() from dynamic to AssetStore'],
        ['c' => 'DBFile', 'm' => 'getTag', 'n' => 'Changed return type for method DBFile::getTag() from dynamic to string'],
        ['c' => 'DBFile', 'm' => 'getTitle', 'n' => 'Changed return type for method DBFile::getTitle() from dynamic to string'],
        ['c' => 'DBFile', 'm' => 'isValidFilename', 'n' => 'Changed return type for method DBFile::isValidFilename() from dynamic to bool'],
        ['c' => 'DBFile', 'm' => 'setAllowedCategories', 'n' => 'Changed return type for method DBFile::setAllowedCategories() from dynamic to DBFile'],
        ['c' => 'DBFile', 'm' => 'setOriginal', 'n' => 'Changed return type for method DBFile::setOriginal() from dynamic to DBFile'],
        ['c' => 'DBFile', 'm' => 'validate', 'n' => 'Changed return type for method DBFile::validate() from dynamic to ValidationResult'],
        ['c' => 'File', 'm' => 'forTemplate', 'n' => 'Changed return type for method File::forTemplate() from dynamic to string'],
        ['c' => 'File', 'm' => 'getPermissionChecker', 'n' => 'Changed return type for method File::getPermissionChecker() from dynamic to PermissionChecker'],
        ['c' => 'File', 'm' => 'getTag', 'n' => 'Changed return type for method File::getTag() from dynamic to string'],
        ['c' => 'File', 'm' => 'getTreeTitle', 'n' => 'Changed return type for method File::getTreeTitle() from dynamic to string'],

        ['c' => 'Image_Backend', 'm' => 'crop', 'n' => 'Changed return type for method Image_Backend::crop() from dynamic to Image_Backend|null'],
        ['c' => 'Image_Backend', 'm' => 'croppedResize', 'n' => 'Changed return type for method Image_Backend::croppedResize() from dynamic to Image_Backend|null'],
        ['c' => 'Image_Backend', 'm' => 'getHeight', 'n' => 'Changed return type for method Image_Backend::getHeight() from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'getImageResource', 'n' => 'Changed return type for method Image_Backend::getImageResource() from dynamic to mixed'],
        ['c' => 'Image_Backend', 'm' => 'getWidth', 'n' => 'Changed return type for method Image_Backend::getWidth() from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'loadFrom', 'n' => 'Changed return type for method Image_Backend::loadFrom() from dynamic to Image_Backend'],
        ['c' => 'Image_Backend', 'm' => 'loadFromContainer', 'n' => 'Changed return type for method Image_Backend::loadFromContainer() from dynamic to Image_Backend'],
        ['c' => 'Image_Backend', 'm' => 'paddedResize', 'n' => 'Changed return type for method Image_Backend::paddedResize() from dynamic to Image_Backend|null'],
        ['c' => 'Image_Backend', 'm' => 'resize', 'n' => 'Changed return type for method Image_Backend::resize() from dynamic to Image_Backend|null'],
        ['c' => 'Image_Backend', 'm' => 'resizeByHeight', 'n' => 'Changed return type for method Image_Backend::resizeByHeight() from dynamic to Image_Backend|null'],
        ['c' => 'Image_Backend', 'm' => 'resizeByWidth', 'n' => 'Changed return type for method Image_Backend::resizeByWidth() from dynamic to Image_Backend|null'],
        ['c' => 'Image_Backend', 'm' => 'resizeRatio', 'n' => 'Changed return type for method Image_Backend::resizeRatio() from dynamic to Image_Backend|null'],
        ['c' => 'Image_Backend', 'm' => 'setImageResource', 'n' => 'Changed return type for method Image_Backend::setImageResource() from dynamic to Image_Backend'],
        ['c' => 'Image_Backend', 'm' => 'setQuality', 'n' => 'Changed return type for method Image_Backend::setQuality() from dynamic to Image_Backend'],
        ['c' => 'Image_Backend', 'm' => 'writeTo', 'n' => 'Changed return type for method Image_Backend::writeTo() from dynamic to bool'],
        ['c' => 'Image_Backend', 'm' => 'writeToStore', 'n' => 'Changed return type for method Image_Backend::writeToStore() from dynamic to array'],

        ['c' => 'InterventionBackend', 'm' => 'createCloneWithResource', 'n' => 'Changed return type for method InterventionBackend::createCloneWithResource() from dynamic to InterventionBackend|null'],
        ['c' => 'InterventionBackend', 'm' => 'getAssetContainer', 'n' => 'Changed return type for method InterventionBackend::getAssetContainer() from dynamic to AssetContainer|null'],
        ['c' => 'InterventionBackend', 'm' => 'getCache', 'n' => 'Changed return type for method InterventionBackend::getCache() from dynamic to Psr\\SimpleCache\\CacheInterface'],
        ['c' => 'InterventionBackend', 'm' => 'getDimensionCacheKey', 'n' => 'Changed return type for method InterventionBackend::getDimensionCacheKey() from dynamic to string'],
        ['c' => 'InterventionBackend', 'm' => 'getDimensions', 'n' => 'Changed return type for method InterventionBackend::getDimensions() from dynamic to array'],
        ['c' => 'InterventionBackend', 'm' => 'getErrorCacheKey', 'n' => 'Changed return type for method InterventionBackend::getErrorCacheKey() from dynamic to string'],
        ['c' => 'InterventionBackend', 'm' => 'getImageManager', 'n' => 'Changed return type for method InterventionBackend::getImageManager() from dynamic to Intervention\\Image\\ImageManager'],
        ['c' => 'InterventionBackend', 'm' => 'getQuality', 'n' => 'Changed return type for method InterventionBackend::getQuality() from dynamic to int'],
        ['c' => 'InterventionBackend', 'm' => 'getResourceDimensions', 'n' => 'Changed return type for method InterventionBackend::getResourceDimensions() from dynamic to array'],
        ['c' => 'InterventionBackend', 'm' => 'getTempPath', 'n' => 'Changed return type for method InterventionBackend::getTempPath() from dynamic to string|null'],
        ['c' => 'InterventionBackend', 'm' => 'hasFailed', 'n' => 'Changed return type for method InterventionBackend::hasFailed() from dynamic to string|null'],
        ['c' => 'InterventionBackend', 'm' => 'isStreamReadable', 'n' => 'Changed return type for method InterventionBackend::isStreamReadable() from dynamic to bool'],
        ['c' => 'InterventionBackend', 'm' => 'markFailed', 'n' => 'Changed return type for method InterventionBackend::markFailed() from dynamic to void'],
        ['c' => 'InterventionBackend', 'm' => 'markSuccess', 'n' => 'Changed return type for method InterventionBackend::markSuccess() from dynamic to void'],
        ['c' => 'InterventionBackend', 'm' => 'setAssetContainer', 'n' => 'Changed return type for method InterventionBackend::setAssetContainer() from dynamic to InterventionBackend'],
        ['c' => 'InterventionBackend', 'm' => 'setCache', 'n' => 'Changed return type for method InterventionBackend::setCache() from dynamic to InterventionBackend'],
        ['c' => 'InterventionBackend', 'm' => 'setImageManager', 'n' => 'Changed return type for method InterventionBackend::setImageManager() from dynamic to InterventionBackend'],
        ['c' => 'InterventionBackend', 'm' => 'setTempPath', 'n' => 'Changed return type for method InterventionBackend::setTempPath() from dynamic to InterventionBackend'],
        ['c' => 'InterventionBackend', 'm' => 'warmCache', 'n' => 'Changed return type for method InterventionBackend::warmCache() from dynamic to void'],

        ['c' => 'BlogObject', 'm' => 'validate', 'n' => 'Changed return type for method BlogObject::validate() from dynamic to ValidationResult'],

        ['c' => 'CMSMain', 'm' => 'ExtraTreeTools', 'n' => 'Changed return type for method CMSMain::ExtraTreeTools() from dynamic to string'],
        ['c' => 'CMSMain', 'm' => 'TreeIsFiltered', 'n' => 'Changed return type for method CMSMain::TreeIsFiltered() from dynamic to bool'],
        ['c' => 'CMSMain', 'm' => 'getArchiveWarningMessage', 'n' => 'Changed return type for method CMSMain::getArchiveWarningMessage() from dynamic to string'],
        ['c' => 'CMSMain', 'm' => 'getHintsCache', 'n' => 'Changed return type for method CMSMain::getHintsCache() from dynamic to Psr\\SimpleCache\\CacheInterface|null'],
        ['c' => 'CMSMain', 'm' => 'getSearchForm', 'n' => 'Changed return type for method CMSMain::getSearchForm() from dynamic to SearchContextForm'],
        ['c' => 'CMSMain', 'm' => 'getTreeNodeClasses', 'n' => 'Changed return type for method CMSMain::getTreeNodeClasses() from dynamic to string'],
        ['c' => 'CMSMain', 'm' => 'setHintsCache', 'n' => 'Changed return type for method CMSMain::setHintsCache() from dynamic to CMSMain'],
        ['c' => 'CMSSiteTreeFilter', 'm' => 'getFilteredPages', 'n' => 'Changed return type for method CMSSiteTreeFilter::getFilteredPages() from dynamic to DataList'],
        ['c' => 'CMSSiteTreeFilter', 'm' => 'get_all_filters', 'n' => 'Changed return type for method CMSSiteTreeFilter::get_all_filters() from dynamic to array'],
        ['c' => 'CMSSiteTreeFilter_ChangedPages', 'm' => 'title', 'n' => 'Changed return type for method CMSSiteTreeFilter_ChangedPages::title() from dynamic to string'],
        ['c' => 'CMSSiteTreeFilter_DeletedPages', 'm' => 'title', 'n' => 'Changed return type for method CMSSiteTreeFilter_DeletedPages::title() from dynamic to string'],
        ['c' => 'CMSSiteTreeFilter_PublishedPages', 'm' => 'title', 'n' => 'Changed return type for method CMSSiteTreeFilter_PublishedPages::title() from dynamic to string'],
        ['c' => 'CMSSiteTreeFilter_Search', 'm' => 'title', 'n' => 'Changed return type for method CMSSiteTreeFilter_Search::title() from dynamic to string'],
        ['c' => 'CMSSiteTreeFilter_StatusDeletedPages', 'm' => 'title', 'n' => 'Changed return type for method CMSSiteTreeFilter_StatusDeletedPages::title() from dynamic to string'],
        ['c' => 'CMSSiteTreeFilter_StatusDraftPages', 'm' => 'title', 'n' => 'Changed return type for method CMSSiteTreeFilter_StatusDraftPages::title() from dynamic to string'],
        ['c' => 'CMSSiteTreeFilter_StatusRemovedFromDraftPages', 'm' => 'title', 'n' => 'Changed return type for method CMSSiteTreeFilter_StatusRemovedFromDraftPages::title() from dynamic to string'],
        ['c' => 'SiteTree', 'm' => 'CMSTreeClasses', 'n' => 'Changed return type for method SiteTree::CMSTreeClasses() from dynamic to string'],
        ['c' => 'SiteTree', 'm' => 'allowedChildren', 'n' => 'Changed return type for method SiteTree::allowedChildren() from dynamic to array'],
        ['c' => 'SiteTree', 'm' => 'canAddChildren', 'n' => 'Changed return type for method SiteTree::canAddChildren() from dynamic to bool'],
        ['c' => 'SiteTree', 'm' => 'defaultChild', 'n' => 'Changed return type for method SiteTree::defaultChild() from dynamic to string|null'],
        ['c' => 'SiteTree', 'm' => 'defaultParent', 'n' => 'Changed return type for method SiteTree::defaultParent() from dynamic to string|null'],
        ['c' => 'SiteTree', 'm' => 'duplicateWithChildren', 'n' => 'Changed return type for method SiteTree::duplicateWithChildren() from dynamic to DataObject'],
        ['c' => 'SiteTree', 'm' => 'getPermissionChecker', 'n' => 'Changed return type for method SiteTree::getPermissionChecker() from dynamic to PermissionChecker'],
        ['c' => 'SiteTree', 'm' => 'getStatusFlags', 'n' => 'Changed return type for method SiteTree::getStatusFlags() from dynamic to array'],
        ['c' => 'SiteTree', 'm' => 'getTreeTitle', 'n' => 'Changed return type for method SiteTree::getTreeTitle() from dynamic to string'],
        ['c' => 'VirtualPage', 'm' => 'CMSTreeClasses', 'n' => 'Changed return type for method VirtualPage::CMSTreeClasses() from dynamic to string'],
        ['c' => 'VirtualPage', 'm' => '__get', 'n' => 'Changed return type for method VirtualPage::__get() from dynamic to mixed'],
        ['c' => 'VirtualPage', 'm' => 'castingHelper', 'n' => 'Changed return type for method VirtualPage::castingHelper() from dynamic to string|null'],
        ['c' => 'VirtualPage', 'm' => 'getField', 'n' => 'Changed return type for method VirtualPage::getField() from dynamic to mixed'],
        ['c' => 'VirtualPage', 'm' => 'getViewerTemplates', 'n' => 'Changed return type for method VirtualPage::getViewerTemplates() from dynamic to array'],
        ['c' => 'VirtualPage', 'm' => 'hasField', 'n' => 'Changed return type for method VirtualPage::hasField() from dynamic to bool'],
        ['c' => 'VirtualPage', 'm' => 'validate', 'n' => 'Changed return type for method VirtualPage::validate() from dynamic to ValidationResult'],

        ['c' => 'BuildTask', 'm' => 'getDescription', 'n' => 'Changed return type for method BuildTask::getDescription() from dynamic to string'],
        ['c' => 'BuildTask', 'm' => 'getTitle', 'n' => 'Changed return type for method BuildTask::getTitle() from dynamic to string'],
        ['c' => 'BuildTask', 'm' => 'isEnabled', 'n' => 'Changed return type for method BuildTask::isEnabled() from dynamic to bool'],
        ['c' => 'BuildTask', 'm' => 'run', 'n' => 'Changed return type for method BuildTask::run() from dynamic to int'],
        ['c' => 'ChangePasswordHandler', 'm' => 'setSessionToken', 'n' => 'Changed return type for method ChangePasswordHandler::setSessionToken() from dynamic to void'],
        ['c' => 'ClassManifestErrorHandler', 'm' => 'handleError', 'n' => 'Changed return type for method ClassManifestErrorHandler::handleError() from dynamic to void'],
        ['c' => 'ConfirmedPasswordField', 'm' => 'getConfirmPasswordField', 'n' => 'Changed return type for method ConfirmedPasswordField::getConfirmPasswordField() from dynamic to PasswordField'],
        ['c' => 'ConfirmedPasswordField', 'm' => 'getMaxLength', 'n' => 'Changed return type for method ConfirmedPasswordField::getMaxLength() from dynamic to int'],
        ['c' => 'ConfirmedPasswordField', 'm' => 'getMinLength', 'n' => 'Changed return type for method ConfirmedPasswordField::getMinLength() from dynamic to int'],
        ['c' => 'ConfirmedPasswordField', 'm' => 'getPasswordField', 'n' => 'Changed return type for method ConfirmedPasswordField::getPasswordField() from dynamic to PasswordField'],
        ['c' => 'ConfirmedPasswordField', 'm' => 'getRequireExistingPassword', 'n' => 'Changed return type for method ConfirmedPasswordField::getRequireExistingPassword() from dynamic to bool'],
        ['c' => 'ConfirmedPasswordField', 'm' => 'getRequireStrongPassword', 'n' => 'Changed return type for method ConfirmedPasswordField::getRequireStrongPassword() from dynamic to bool'],
        ['c' => 'ConfirmedPasswordField', 'm' => 'setMaxLength', 'n' => 'Changed return type for method ConfirmedPasswordField::setMaxLength() from dynamic to ConfirmedPasswordField'],
        ['c' => 'ConfirmedPasswordField', 'm' => 'setMinLength', 'n' => 'Changed return type for method ConfirmedPasswordField::setMinLength() from dynamic to ConfirmedPasswordField'],
        ['c' => 'ConfirmedPasswordField', 'm' => 'setRequireExistingPassword', 'n' => 'Changed return type for method ConfirmedPasswordField::setRequireExistingPassword() from dynamic to ConfirmedPasswordField'],
        ['c' => 'ConfirmedPasswordField', 'm' => 'setRequireStrongPassword', 'n' => 'Changed return type for method ConfirmedPasswordField::setRequireStrongPassword() from dynamic to ConfirmedPasswordField'],
        ['c' => 'Controller', 'm' => 'curr', 'n' => 'Changed return type for method Controller::curr() from dynamic to Controller|null'],
        ['c' => 'Controller', 'm' => 'join_links', 'n' => 'Changed return type for method Controller::join_links() from dynamic to string'],
        ['c' => 'Controller', 'm' => 'render', 'n' => 'Changed return type for method Controller::render() from dynamic to DBHTMLText'],
        ['c' => 'Convert', 'm' => 'linkIfMatch', 'n' => 'Changed return type for method Convert::linkIfMatch() from dynamic to string'],
        ['c' => 'Cookie', 'm' => 'force_expiry', 'n' => 'Changed return type for method Cookie::force_expiry() from dynamic to void'],
        ['c' => 'Cookie', 'm' => 'get', 'n' => 'Changed return type for method Cookie::get() from dynamic to string|null'],
        ['c' => 'Cookie', 'm' => 'get_all', 'n' => 'Changed return type for method Cookie::get_all() from dynamic to array'],
        ['c' => 'Cookie', 'm' => 'get_inst', 'n' => 'Changed return type for method Cookie::get_inst() from dynamic to Cookie_Backend'],
        ['c' => 'CookieAuthenticationHandler', 'm' => 'getDeviceCookieName', 'n' => 'Changed return type for method CookieAuthenticationHandler::getDeviceCookieName() from dynamic to string'],
        ['c' => 'CookieAuthenticationHandler', 'm' => 'getTokenCookieName', 'n' => 'Changed return type for method CookieAuthenticationHandler::getTokenCookieName() from dynamic to string'],
        ['c' => 'CookieAuthenticationHandler', 'm' => 'getTokenCookieSecure', 'n' => 'Changed return type for method CookieAuthenticationHandler::getTokenCookieSecure() from dynamic to bool'],
        ['c' => 'CookieAuthenticationHandler', 'm' => 'setDeviceCookieName', 'n' => 'Changed return type for method CookieAuthenticationHandler::setDeviceCookieName() from dynamic to CookieAuthenticationHandler'],
        ['c' => 'CookieAuthenticationHandler', 'm' => 'setTokenCookieName', 'n' => 'Changed return type for method CookieAuthenticationHandler::setTokenCookieName() from dynamic to CookieAuthenticationHandler'],
        ['c' => 'CookieAuthenticationHandler', 'm' => 'setTokenCookieSecure', 'n' => 'Changed return type for method CookieAuthenticationHandler::setTokenCookieSecure() from dynamic to CookieAuthenticationHandler'],
        ['c' => 'CookieJar', 'm' => 'outputCookie', 'n' => 'Changed return type for method CookieJar::outputCookie() from dynamic to bool'],
        ['c' => 'Cookie_Backend', 'm' => 'forceExpiry', 'n' => 'Changed return type for method Cookie_Backend::forceExpiry() from dynamic to void'],
        ['c' => 'Cookie_Backend', 'm' => 'get', 'n' => 'Changed return type for method Cookie_Backend::get() from dynamic to string|null'],
        ['c' => 'Cookie_Backend', 'm' => 'getAll', 'n' => 'Changed return type for method Cookie_Backend::getAll() from dynamic to array'],
        ['c' => 'Cookie_Backend', 'm' => 'set', 'n' => 'Changed return type for method Cookie_Backend::set() from dynamic to void'],
        ['c' => 'DBBoolean', 'm' => 'Nice', 'n' => 'Changed return type for method DBBoolean::Nice() from dynamic to string'],
        ['c' => 'DBBoolean', 'm' => 'NiceAsBoolean', 'n' => 'Changed return type for method DBBoolean::NiceAsBoolean() from dynamic to string'],
        ['c' => 'DBClassNameTrait', 'm' => 'getBaseClass', 'n' => 'Changed return type for method DBClassNameTrait::getBaseClass() from dynamic to string'],
        ['c' => 'DBClassNameTrait', 'm' => 'getEnum', 'n' => 'Changed return type for method DBClassNameTrait::getEnum() from dynamic to array'],
        ['c' => 'DBClassNameTrait', 'm' => 'getShortName', 'n' => 'Changed return type for method DBClassNameTrait::getShortName() from dynamic to string'],
        ['c' => 'DBClassNameTrait', 'm' => 'setBaseClass', 'n' => 'Changed return type for method DBClassNameTrait::setBaseClass() from dynamic to DBClassNameTrait'],
        ['c' => 'DBClassNameTrait', 'm' => 'setValue', 'n' => 'Changed return type for method DBClassNameTrait::setValue() from dynamic to DBClassNameTrait'],
        ['c' => 'DBComposite', 'm' => 'bindTo', 'n' => 'Changed return type for method DBComposite::bindTo() from dynamic to void'],
        ['c' => 'DBComposite', 'm' => 'compositeDatabaseFields', 'n' => 'Changed return type for method DBComposite::compositeDatabaseFields() from dynamic to array'],
        ['c' => 'DBComposite', 'm' => 'dbObject', 'n' => 'Changed return type for method DBComposite::dbObject() from dynamic to DBField|null'],
        ['c' => 'DBComposite', 'm' => 'isChanged', 'n' => 'Changed return type for method DBComposite::isChanged() from dynamic to bool'],
        ['c' => 'DBCurrency', 'm' => 'Whole', 'n' => 'Changed return type for method DBCurrency::Whole() from dynamic to string'],
        ['c' => 'DBDate', 'm' => 'DayOfMonth', 'n' => 'Changed return type for method DBDate::DayOfMonth() from dynamic to string'],
        ['c' => 'DBDate', 'm' => 'DayOfWeek', 'n' => 'Changed return type for method DBDate::DayOfWeek() from dynamic to string'],
        ['c' => 'DBDate', 'm' => 'Format', 'n' => 'Changed return type for method DBDate::Format() from dynamic to string'],
        ['c' => 'DBDate', 'm' => 'FormatFromSettings', 'n' => 'Changed return type for method DBDate::FormatFromSettings() from dynamic to string'],
        ['c' => 'DBDate', 'm' => 'Full', 'n' => 'Changed return type for method DBDate::Full() from dynamic to string'],
        ['c' => 'DBDate', 'm' => 'Long', 'n' => 'Changed return type for method DBDate::Long() from dynamic to string'],
        ['c' => 'DBDate', 'm' => 'Month', 'n' => 'Changed return type for method DBDate::Month() from dynamic to string'],
        ['c' => 'DBDate', 'm' => 'Nice', 'n' => 'Changed return type for method DBDate::Nice() from dynamic to string'],
        ['c' => 'DBDate', 'm' => 'Short', 'n' => 'Changed return type for method DBDate::Short() from dynamic to string'],
        ['c' => 'DBDate', 'm' => 'ShortMonth', 'n' => 'Changed return type for method DBDate::ShortMonth() from dynamic to string'],
        ['c' => 'DBDate', 'm' => 'Year', 'n' => 'Changed return type for method DBDate::Year() from dynamic to string'],
        ['c' => 'DBDate', 'm' => 'getCustomFormatter', 'n' => 'Changed return type for method DBDate::getCustomFormatter() from dynamic to IntlDateFormatter'],
        ['c' => 'DBDate', 'm' => 'getFormatter', 'n' => 'Changed return type for method DBDate::getFormatter() from dynamic to IntlDateFormatter'],
        ['c' => 'DBDate', 'm' => 'getISOFormat', 'n' => 'Changed return type for method DBDate::getISOFormat() from dynamic to string'],
        ['c' => 'DBDate', 'm' => 'getTimestamp', 'n' => 'Changed return type for method DBDate::getTimestamp() from dynamic to int'],
        ['c' => 'DBDate', 'm' => 'parseDate', 'n' => 'Changed return type for method DBDate::parseDate() from dynamic to string|null|false'],
        ['c' => 'DBDatetime', 'm' => 'Date', 'n' => 'Changed return type for method DBDatetime::Date() from dynamic to string'],
        ['c' => 'DBDatetime', 'm' => 'Time', 'n' => 'Changed return type for method DBDatetime::Time() from dynamic to string'],
        ['c' => 'DBDatetime', 'm' => 'Time12', 'n' => 'Changed return type for method DBDatetime::Time12() from dynamic to string'],
        ['c' => 'DBDatetime', 'm' => 'Time24', 'n' => 'Changed return type for method DBDatetime::Time24() from dynamic to string'],
        ['c' => 'DBDatetime', 'm' => 'URLDatetime', 'n' => 'Changed return type for method DBDatetime::URLDatetime() from dynamic to string'],
        ['c' => 'DBDatetime', 'm' => 'clear_mock_now', 'n' => 'Changed return type for method DBDatetime::clear_mock_now() from dynamic to void'],
        ['c' => 'DBDatetime', 'm' => 'now', 'n' => 'Changed return type for method DBDatetime::now() from dynamic to DBDatetime'],
        ['c' => 'DBDatetime', 'm' => 'set_mock_now', 'n' => 'Changed return type for method DBDatetime::set_mock_now() from dynamic to void'],
        ['c' => 'DBDatetime', 'm' => 'withFixedNow', 'n' => 'Changed return type for method DBDatetime::withFixedNow() from dynamic to mixed'],
        ['c' => 'DBDecimal', 'm' => 'Int', 'n' => 'Changed return type for method DBDecimal::Int() from dynamic to int'],
        ['c' => 'DBDecimal', 'm' => 'Nice', 'n' => 'Changed return type for method DBDecimal::Nice() from dynamic to string'],
        ['c' => 'DBEnum', 'm' => 'enumValues', 'n' => 'Changed return type for method DBEnum::enumValues() from dynamic to array'],
        ['c' => 'DBEnum', 'm' => 'flushCache', 'n' => 'Changed return type for method DBEnum::flushCache() from dynamic to ModelData'],
        ['c' => 'DBEnum', 'm' => 'formField', 'n' => 'Changed return type for method DBEnum::formField() from dynamic to SelectField'],
        ['c' => 'DBEnum', 'm' => 'getDefault', 'n' => 'Changed return type for method DBEnum::getDefault() from dynamic to string|null'],
        ['c' => 'DBEnum', 'm' => 'getEnum', 'n' => 'Changed return type for method DBEnum::getEnum() from dynamic to array'],
        ['c' => 'DBEnum', 'm' => 'getEnumObsolete', 'n' => 'Changed return type for method DBEnum::getEnumObsolete() from dynamic to array'],
        ['c' => 'DBEnum', 'm' => 'setDefault', 'n' => 'Changed return type for method DBEnum::setDefault() from dynamic to DBEnum'],
        ['c' => 'DBEnum', 'm' => 'setEnum', 'n' => 'Changed return type for method DBEnum::setEnum() from dynamic to DBEnum'],
        ['c' => 'DBField', 'm' => 'ATT', 'n' => 'Changed return type for method DBField::ATT() from dynamic to string'],
        ['c' => 'DBField', 'm' => 'CDATA', 'n' => 'Changed return type for method DBField::CDATA() from dynamic to string'],
        ['c' => 'DBField', 'm' => 'HTML', 'n' => 'Changed return type for method DBField::HTML() from dynamic to string'],
        ['c' => 'DBField', 'm' => 'HTMLATT', 'n' => 'Changed return type for method DBField::HTMLATT() from dynamic to string'],
        ['c' => 'DBField', 'm' => 'JS', 'n' => 'Changed return type for method DBField::JS() from dynamic to string'],
        ['c' => 'DBField', 'm' => 'JSON', 'n' => 'Changed return type for method DBField::JSON() from dynamic to string'],
        ['c' => 'DBField', 'm' => 'RAW', 'n' => 'Changed return type for method DBField::RAW() from dynamic to mixed'],
        ['c' => 'DBField', 'm' => 'RAWURLATT', 'n' => 'Changed return type for method DBField::RAWURLATT() from dynamic to string'],
        ['c' => 'DBField', 'm' => 'URLATT', 'n' => 'Changed return type for method DBField::URLATT() from dynamic to string'],
        ['c' => 'DBField', 'm' => 'XML', 'n' => 'Changed return type for method DBField::XML() from dynamic to string'],
        ['c' => 'DBField', 'm' => 'create_field', 'n' => 'Changed return type for method DBField::create_field() from dynamic to DBField'],
        ['c' => 'DBField', 'm' => 'debug', 'n' => 'Changed return type for method DBField::debug() from dynamic to string'],
        ['c' => 'DBField', 'm' => 'defaultSearchFilter', 'n' => 'Changed return type for method DBField::defaultSearchFilter() from dynamic to SearchFilter'],
        ['c' => 'DBField', 'm' => 'forTemplate', 'n' => 'Changed return type for method DBField::forTemplate() from dynamic to string'],
        ['c' => 'DBField', 'm' => 'getDefaultValue', 'n' => 'Changed return type for method DBField::getDefaultValue() from dynamic to mixed'],
        ['c' => 'DBField', 'm' => 'getName', 'n' => 'Changed return type for method DBField::getName() from dynamic to string'],
        ['c' => 'DBField', 'm' => 'getOptions', 'n' => 'Changed return type for method DBField::getOptions() from dynamic to array'],
        ['c' => 'DBField', 'm' => 'getSchemaValue', 'n' => 'Changed return type for method DBField::getSchemaValue() from dynamic to mixed'],
        ['c' => 'DBField', 'm' => 'getTable', 'n' => 'Changed return type for method DBField::getTable() from dynamic to string|null'],
        ['c' => 'DBField', 'm' => 'getValue', 'n' => 'Changed return type for method DBField::getValue() from dynamic to mixed'],
        ['c' => 'DBField', 'm' => 'nullValue', 'n' => 'Changed return type for method DBField::nullValue() from dynamic to mixed'],
        ['c' => 'DBField', 'm' => 'prepValueForDB', 'n' => 'Changed return type for method DBField::prepValueForDB() from dynamic to mixed'],
        ['c' => 'DBField', 'm' => 'requireField', 'n' => 'Changed return type for method DBField::requireField() from dynamic to void'],
        ['c' => 'DBField', 'm' => 'saveInto', 'n' => 'Changed return type for method DBField::saveInto() from dynamic to void'],
        ['c' => 'DBField', 'm' => 'scaffoldFormField', 'n' => 'Changed return type for method DBField::scaffoldFormField() from dynamic to FormField|null'],
        ['c' => 'DBField', 'm' => 'scaffoldSearchField', 'n' => 'Changed return type for method DBField::scaffoldSearchField() from dynamic to FormField|null'],
        ['c' => 'DBField', 'm' => 'scalarValueOnly', 'n' => 'Changed return type for method DBField::scalarValueOnly() from dynamic to bool'],
        ['c' => 'DBField', 'm' => 'setArrayValue', 'n' => 'Changed return type for method DBField::setArrayValue() from dynamic to DBField'],
        ['c' => 'DBField', 'm' => 'setDefaultValue', 'n' => 'Changed return type for method DBField::setDefaultValue() from dynamic to DBField'],
        ['c' => 'DBField', 'm' => 'setName', 'n' => 'Changed return type for method DBField::setName() from dynamic to DBField'],
        ['c' => 'DBField', 'm' => 'setOptions', 'n' => 'Changed return type for method DBField::setOptions() from dynamic to DBField'],
        ['c' => 'DBField', 'm' => 'setTable', 'n' => 'Changed return type for method DBField::setTable() from dynamic to DBField'],
        ['c' => 'DBField', 'm' => 'setValue', 'n' => 'Changed return type for method DBField::setValue() from dynamic to DBField'],
        ['c' => 'DBField', 'm' => 'writeToManipulation', 'n' => 'Changed return type for method DBField::writeToManipulation() from dynamic to void'],
        ['c' => 'DBFloat', 'm' => 'Nice', 'n' => 'Changed return type for method DBFloat::Nice() from dynamic to string'],
        ['c' => 'DBFloat', 'm' => 'NiceRound', 'n' => 'Changed return type for method DBFloat::NiceRound() from dynamic to string'],
        ['c' => 'DBFloat', 'm' => 'Round', 'n' => 'Changed return type for method DBFloat::Round() from dynamic to float'],
        ['c' => 'DBHTMLText', 'm' => 'AbsoluteLinks', 'n' => 'Changed return type for method DBHTMLText::AbsoluteLinks() from dynamic to string'],
        ['c' => 'DBHTMLText', 'm' => 'getProcessShortcodes', 'n' => 'Changed return type for method DBHTMLText::getProcessShortcodes() from dynamic to bool'],
        ['c' => 'DBHTMLText', 'm' => 'getWhitelist', 'n' => 'Changed return type for method DBHTMLText::getWhitelist() from dynamic to array'],
        ['c' => 'DBHTMLText', 'm' => 'setProcessShortcodes', 'n' => 'Changed return type for method DBHTMLText::setProcessShortcodes() from dynamic to DBHTMLText'],
        ['c' => 'DBHTMLText', 'm' => 'setWhitelist', 'n' => 'Changed return type for method DBHTMLText::setWhitelist() from dynamic to DBHTMLText'],
        ['c' => 'DBHTMLText', 'm' => 'whitelistContent', 'n' => 'Changed return type for method DBHTMLText::whitelistContent() from dynamic to mixed'],
        ['c' => 'DBHTMLVarchar', 'm' => 'getProcessShortcodes', 'n' => 'Changed return type for method DBHTMLVarchar::getProcessShortcodes() from dynamic to bool'],
        ['c' => 'DBHTMLVarchar', 'm' => 'setProcessShortcodes', 'n' => 'Changed return type for method DBHTMLVarchar::setProcessShortcodes() from dynamic to DBHTMLVarchar'],
        ['c' => 'DBInt', 'm' => 'Formatted', 'n' => 'Changed return type for method DBInt::Formatted() from dynamic to string'],
        ['c' => 'DBInt', 'm' => 'Nice', 'n' => 'Changed return type for method DBInt::Nice() from dynamic to string'],
        ['c' => 'DBLocale', 'm' => 'Nice', 'n' => 'Changed return type for method DBLocale::Nice() from dynamic to string'],
        ['c' => 'DBLocale', 'm' => 'RFC1766', 'n' => 'Changed return type for method DBLocale::RFC1766() from dynamic to string'],
        ['c' => 'DBLocale', 'm' => 'getLongName', 'n' => 'Changed return type for method DBLocale::getLongName() from dynamic to string'],
        ['c' => 'DBLocale', 'm' => 'getNativeName', 'n' => 'Changed return type for method DBLocale::getNativeName() from dynamic to string'],
        ['c' => 'DBLocale', 'm' => 'getShortName', 'n' => 'Changed return type for method DBLocale::getShortName() from dynamic to string'],
        ['c' => 'DBMoney', 'm' => 'Nice', 'n' => 'Changed return type for method DBMoney::Nice() from dynamic to string'],
        ['c' => 'DBMoney', 'm' => 'getAmount', 'n' => 'Changed return type for method DBMoney::getAmount() from dynamic to mixed'],
        ['c' => 'DBMoney', 'm' => 'getCurrency', 'n' => 'Changed return type for method DBMoney::getCurrency() from dynamic to string|null'],
        ['c' => 'DBMoney', 'm' => 'getFormatter', 'n' => 'Changed return type for method DBMoney::getFormatter() from dynamic to NumberFormatter'],
        ['c' => 'DBMoney', 'm' => 'getLocale', 'n' => 'Changed return type for method DBMoney::getLocale() from dynamic to string'],
        ['c' => 'DBMoney', 'm' => 'getSymbol', 'n' => 'Changed return type for method DBMoney::getSymbol() from dynamic to string'],
        ['c' => 'DBMoney', 'm' => 'hasAmount', 'n' => 'Changed return type for method DBMoney::hasAmount() from dynamic to bool'],
        ['c' => 'DBMoney', 'm' => 'setAmount', 'n' => 'Changed return type for method DBMoney::setAmount() from dynamic to DBMoney'],
        ['c' => 'DBMoney', 'm' => 'setCurrency', 'n' => 'Changed return type for method DBMoney::setCurrency() from dynamic to DBMoney'],
        ['c' => 'DBMoney', 'm' => 'setLocale', 'n' => 'Changed return type for method DBMoney::setLocale() from dynamic to DBMoney'],
        ['c' => 'DBPolymorphicForeignKey', 'm' => 'getClassValue', 'n' => 'Changed return type for method DBPolymorphicForeignKey::getClassValue() from dynamic to string|null'],
        ['c' => 'DBPolymorphicForeignKey', 'm' => 'getIDValue', 'n' => 'Changed return type for method DBPolymorphicForeignKey::getIDValue() from dynamic to int|null'],
        ['c' => 'DBPrimaryKey', 'm' => 'getAutoIncrement', 'n' => 'Changed return type for method DBPrimaryKey::getAutoIncrement() from dynamic to bool'],
        ['c' => 'DBPrimaryKey', 'm' => 'setAutoIncrement', 'n' => 'Changed return type for method DBPrimaryKey::setAutoIncrement() from dynamic to DBPrimaryKey'],
        ['c' => 'DBString', 'm' => 'LimitCharacters', 'n' => 'Changed return type for method DBString::LimitCharacters() from dynamic to string'],
        ['c' => 'DBString', 'm' => 'LimitCharactersToClosestWord', 'n' => 'Changed return type for method DBString::LimitCharactersToClosestWord() from dynamic to string'],
        ['c' => 'DBString', 'm' => 'LimitWordCount', 'n' => 'Changed return type for method DBString::LimitWordCount() from dynamic to string'],
        ['c' => 'DBString', 'm' => 'LowerCase', 'n' => 'Changed return type for method DBString::LowerCase() from dynamic to string'],
        ['c' => 'DBString', 'm' => 'Plain', 'n' => 'Changed return type for method DBString::Plain() from dynamic to string'],
        ['c' => 'DBString', 'm' => 'UpperCase', 'n' => 'Changed return type for method DBString::UpperCase() from dynamic to string'],
        ['c' => 'DBString', 'm' => 'getNullifyEmpty', 'n' => 'Changed return type for method DBString::getNullifyEmpty() from dynamic to bool'],
        ['c' => 'DBString', 'm' => 'setNullifyEmpty', 'n' => 'Changed return type for method DBString::setNullifyEmpty() from dynamic to DBString'],
        ['c' => 'DBText', 'm' => 'ContextSummary', 'n' => 'Changed return type for method DBText::ContextSummary() from dynamic to string'],
        ['c' => 'DBText', 'm' => 'FirstParagraph', 'n' => 'Changed return type for method DBText::FirstParagraph() from dynamic to string'],
        ['c' => 'DBText', 'm' => 'FirstSentence', 'n' => 'Changed return type for method DBText::FirstSentence() from dynamic to string'],
        ['c' => 'DBText', 'm' => 'LimitSentences', 'n' => 'Changed return type for method DBText::LimitSentences() from dynamic to string'],
        ['c' => 'DBText', 'm' => 'Summary', 'n' => 'Changed return type for method DBText::Summary() from dynamic to string'],
        ['c' => 'DBTime', 'm' => 'Format', 'n' => 'Changed return type for method DBTime::Format() from dynamic to string'],
        ['c' => 'DBTime', 'm' => 'FormatFromSettings', 'n' => 'Changed return type for method DBTime::FormatFromSettings() from dynamic to string'],
        ['c' => 'DBTime', 'm' => 'Nice', 'n' => 'Changed return type for method DBTime::Nice() from dynamic to string'],
        ['c' => 'DBTime', 'm' => 'Short', 'n' => 'Changed return type for method DBTime::Short() from dynamic to string'],
        ['c' => 'DBTime', 'm' => 'getFormatter', 'n' => 'Changed return type for method DBTime::getFormatter() from dynamic to IntlDateFormatter'],
        ['c' => 'DBTime', 'm' => 'getISOFormat', 'n' => 'Changed return type for method DBTime::getISOFormat() from dynamic to string'],
        ['c' => 'DBTime', 'm' => 'getTimestamp', 'n' => 'Changed return type for method DBTime::getTimestamp() from dynamic to int'],
        ['c' => 'DBTime', 'm' => 'parseTime', 'n' => 'Changed return type for method DBTime::parseTime() from dynamic to string|null|false'],
        ['c' => 'DBVarchar', 'm' => 'Initial', 'n' => 'Changed return type for method DBVarchar::Initial() from dynamic to string'],
        ['c' => 'DBVarchar', 'm' => 'RTF', 'n' => 'Changed return type for method DBVarchar::RTF() from dynamic to string'],
        ['c' => 'DBVarchar', 'm' => 'URL', 'n' => 'Changed return type for method DBVarchar::URL() from dynamic to string'],
        ['c' => 'DBVarchar', 'm' => 'getSize', 'n' => 'Changed return type for method DBVarchar::getSize() from dynamic to int'],
        ['c' => 'DataList', 'm' => 'columnUnique', 'n' => 'Changed return type for method DataList::columnUnique() from dynamic to array'],
        ['c' => 'DataList', 'm' => 'dbObject', 'n' => 'Changed return type for method DataList::dbObject() from dynamic to DBField|null'],
        ['c' => 'DataList', 'm' => 'debug', 'n' => 'Changed return type for method DataList::debug() from dynamic to string'],
        ['c' => 'DataList', 'm' => 'excludeAny', 'n' => 'Changed return type for method DataList::excludeAny() from dynamic to SS_List'],
        ['c' => 'DataObject', 'm' => 'classDescription', 'n' => 'Changed return type for method DataObject::classDescription() from dynamic to string|null'],
        ['c' => 'DataObject', 'm' => 'dbObject', 'n' => 'Changed return type for method DataObject::dbObject() from dynamic to DBField|null'],
        ['c' => 'DataObject', 'm' => 'debug', 'n' => 'Changed return type for method DataObject::debug() from dynamic to string'],
        ['c' => 'DataObject', 'm' => 'defaultSearchFilters', 'n' => 'Changed return type for method DataObject::defaultSearchFilters() from dynamic to array'],
        ['c' => 'DataObject', 'm' => 'flushCache', 'n' => 'Changed return type for method DataObject::flushCache() from dynamic to ModelData'],
        ['c' => 'DataObject', 'm' => 'i18n_classDescription', 'n' => 'Changed return type for method DataObject::i18n_classDescription() from dynamic to string|null'],
        ['c' => 'DataObject', 'm' => 'validate', 'n' => 'Changed return type for method DataObject::validate() from dynamic to ValidationResult'],
        ['c' => 'DataObjectInterface', 'm' => '__get', 'n' => 'Changed return type for method DataObjectInterface::__get() from dynamic to mixed'],
        ['c' => 'DataQuery', 'm' => 'conjunctiveGroup', 'n' => 'Changed return type for method DataQuery::conjunctiveGroup() from dynamic to DataQuery_SubGroup'],
        ['c' => 'DataQuery', 'm' => 'disjunctiveGroup', 'n' => 'Changed return type for method DataQuery::disjunctiveGroup() from dynamic to DataQuery_SubGroup'],
        ['c' => 'DateField', 'm' => 'internalToFrontend', 'n' => 'Changed return type for method DateField::internalToFrontend() from dynamic to string|null'],
        ['c' => 'DateField', 'm' => 'tidyInternal', 'n' => 'Changed return type for method DateField::tidyInternal() from dynamic to string|null'],
        ['c' => 'DatetimeField', 'm' => 'internalToFrontend', 'n' => 'Changed return type for method DatetimeField::internalToFrontend() from dynamic to string|null'],
        ['c' => 'DatetimeField', 'm' => 'tidyInternal', 'n' => 'Changed return type for method DatetimeField::tidyInternal() from dynamic to string|null'],
        ['c' => 'DefaultCacheFactory', 'm' => 'isPHPFilesSupported', 'n' => 'Changed return type for method DefaultCacheFactory::isPHPFilesSupported() from dynamic to bool'],
        ['c' => 'EagerLoadedList', 'm' => 'debug', 'n' => 'Changed return type for method EagerLoadedList::debug() from dynamic to string'],
        ['c' => 'EagerLoadedList', 'm' => 'excludeAny', 'n' => 'Changed return type for method EagerLoadedList::excludeAny() from EagerLoadedList to SS_List'],
        ['c' => 'Email', 'm' => 'getData', 'n' => 'Changed return type for method Email::getData() from SilverStripe\\View\\ViewableData to ModelData'],
        ['c' => 'Email', 'm' => 'getHTMLTemplate', 'n' => 'Changed return type for method Email::getHTMLTemplate() from string to string|array'],
        ['c' => 'Factory', 'm' => 'create', 'n' => 'Changed return type for method Factory::create() from dynamic to object|null'],
        ['c' => 'FieldList', 'm' => 'HiddenFields', 'n' => 'Changed return type for method FieldList::HiddenFields() from dynamic to FieldList'],
        ['c' => 'FieldList', 'm' => 'VisibleFields', 'n' => 'Changed return type for method FieldList::VisibleFields() from dynamic to FieldList'],
        ['c' => 'FieldList', 'm' => '__clone', 'n' => 'Changed return type for method FieldList::__clone() from dynamic to void'],
        ['c' => 'FieldList', 'm' => 'addFieldToTab', 'n' => 'Changed return type for method FieldList::addFieldToTab() from dynamic to FieldList'],
        ['c' => 'FieldList', 'm' => 'addFieldsToTab', 'n' => 'Changed return type for method FieldList::addFieldsToTab() from dynamic to FieldList'],
        ['c' => 'FieldList', 'm' => 'changeFieldOrder', 'n' => 'Changed return type for method FieldList::changeFieldOrder() from dynamic to void'],
        ['c' => 'FieldList', 'm' => 'dataFieldByName', 'n' => 'Changed return type for method FieldList::dataFieldByName() from dynamic to FormField|null'],
        ['c' => 'FieldList', 'm' => 'dataFieldNames', 'n' => 'Changed return type for method FieldList::dataFieldNames() from dynamic to array'],
        ['c' => 'FieldList', 'm' => 'dataFields', 'n' => 'Changed return type for method FieldList::dataFields() from dynamic to array'],
        ['c' => 'FieldList', 'm' => 'fieldByName', 'n' => 'Changed return type for method FieldList::fieldByName() from dynamic to FormField|null'],
        ['c' => 'FieldList', 'm' => 'fieldNameError', 'n' => 'Changed return type for method FieldList::fieldNameError() from dynamic to void'],
        ['c' => 'FieldList', 'm' => 'fieldPosition', 'n' => 'Changed return type for method FieldList::fieldPosition() from dynamic to int|false'],
        ['c' => 'FieldList', 'm' => 'findOrMakeTab', 'n' => 'Changed return type for method FieldList::findOrMakeTab() from dynamic to Tab|[TabSet`](api:SilverStripe\\Forms\\TabSet)'],
        ['c' => 'FieldList', 'm' => 'findTab', 'n' => 'Changed return type for method FieldList::findTab() from dynamic to Tab|[TabSet](api:SilverStripe\\Forms\\TabSet)|null`'],
        ['c' => 'FieldList', 'm' => 'flattenFields', 'n' => 'Changed return type for method FieldList::flattenFields() from dynamic to FieldList'],
        ['c' => 'FieldList', 'm' => 'flushFieldsCache', 'n' => 'Changed return type for method FieldList::flushFieldsCache() from dynamic to void'],
        ['c' => 'FieldList', 'm' => 'forTemplate', 'n' => 'Changed return type for method FieldList::forTemplate() from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'getContainerField', 'n' => 'Changed return type for method FieldList::getContainerField() from dynamic to CompositeField|null'],
        ['c' => 'FieldList', 'm' => 'hasTabSet', 'n' => 'Changed return type for method FieldList::hasTabSet() from dynamic to bool'],
        ['c' => 'FieldList', 'm' => 'insertAfter', 'n' => 'Changed return type for method FieldList::insertAfter() from FormField|bool to FormField|null'],
        ['c' => 'FieldList', 'm' => 'insertBefore', 'n' => 'Changed return type for method FieldList::insertBefore() from FormField|bool to FormField|null'],
        ['c' => 'FieldList', 'm' => 'makeFieldReadonly', 'n' => 'Changed return type for method FieldList::makeFieldReadonly() from dynamic to void'],
        ['c' => 'FieldList', 'm' => 'makeReadonly', 'n' => 'Changed return type for method FieldList::makeReadonly() from dynamic to FieldList'],
        ['c' => 'FieldList', 'm' => 'recursiveWalk', 'n' => 'Changed return type for method FieldList::recursiveWalk() from dynamic to void'],
        ['c' => 'FieldList', 'm' => 'removeByName', 'n' => 'Changed return type for method FieldList::removeByName() from dynamic to FieldList'],
        ['c' => 'FieldList', 'm' => 'removeFieldFromTab', 'n' => 'Changed return type for method FieldList::removeFieldFromTab() from dynamic to FieldList'],
        ['c' => 'FieldList', 'm' => 'removeFieldsFromTab', 'n' => 'Changed return type for method FieldList::removeFieldsFromTab() from dynamic to FieldList'],
        ['c' => 'FieldList', 'm' => 'renameField', 'n' => 'Changed return type for method FieldList::renameField() from dynamic to bool'],
        ['c' => 'FieldList', 'm' => 'replaceField', 'n' => 'Changed return type for method FieldList::replaceField() from dynamic to bool'],
        ['c' => 'FieldList', 'm' => 'rootFieldList', 'n' => 'Changed return type for method FieldList::rootFieldList() from dynamic to FieldList'],
        ['c' => 'FieldList', 'm' => 'saveableFields', 'n' => 'Changed return type for method FieldList::saveableFields() from dynamic to array'],
        ['c' => 'FieldList', 'm' => 'setContainerField', 'n' => 'Changed return type for method FieldList::setContainerField() from dynamic to FieldList'],
        ['c' => 'FieldList', 'm' => 'setForm', 'n' => 'Changed return type for method FieldList::setForm() from dynamic to FieldList'],
        ['c' => 'FieldList', 'm' => 'setValues', 'n' => 'Changed return type for method FieldList::setValues() from dynamic to FieldList'],
        ['c' => 'FieldList', 'm' => 'transform', 'n' => 'Changed return type for method FieldList::transform() from dynamic to FieldList'],
        ['c' => 'Form', 'm' => 'debug', 'n' => 'Changed return type for method Form::debug() from dynamic to string'],
        ['c' => 'Form', 'm' => 'forTemplate', 'n' => 'Changed return type for method Form::forTemplate() from dynamic to string'],
        ['c' => 'FormField', 'm' => 'debug', 'n' => 'Changed return type for method FormField::debug() from dynamic to string'],
        ['c' => 'FormField', 'm' => 'forTemplate', 'n' => 'Changed return type for method FormField::forTemplate() from dynamic to string'],
        ['c' => 'FormField', 'm' => 'getName', 'n' => 'Changed return type for method FormField::getName() from dynamic to string'],
        ['c' => 'FormField', 'm' => 'validate', 'n' => 'Changed return type for method FormField::validate() from dynamic to ValidationResult'],
        ['c' => 'FormRequestHandler', 'm' => 'forTemplate', 'n' => 'Changed return type for method FormRequestHandler::forTemplate() from dynamic to string'],
        ['c' => 'GridFieldDetailForm', 'm' => 'getRecordFromRequest', 'n' => 'Changed return type for method GridFieldDetailForm::getRecordFromRequest() from SilverStripe\\View\\ViewableData|null to ModelData|null'],
        ['c' => 'GridFieldFilterHeader', 'm' => 'getSearchForm', 'n' => 'Changed return type for method GridFieldFilterHeader::getSearchForm() from dynamic to SearchContextForm|null'],
        ['c' => 'Group', 'm' => 'getTreeTitle', 'n' => 'Changed return type for method Group::getTreeTitle() from dynamic to string'],
        ['c' => 'HTMLEditorConfig', 'm' => 'get', 'n' => 'Changed return type for method HTMLEditorConfig::get() from dynamic to HTMLEditorConfig'],
        ['c' => 'HTMLEditorConfig', 'm' => 'getAttributes', 'n' => 'Changed return type for method HTMLEditorConfig::getAttributes() from dynamic to array'],
        ['c' => 'HTMLEditorConfig', 'm' => 'getConfigSchemaData', 'n' => 'Changed return type for method HTMLEditorConfig::getConfigSchemaData() from dynamic to array'],
        ['c' => 'HTMLEditorConfig', 'm' => 'getOption', 'n' => 'Changed return type for method HTMLEditorConfig::getOption() from dynamic to mixed'],
        ['c' => 'HTMLEditorConfig', 'm' => 'getThemes', 'n' => 'Changed return type for method HTMLEditorConfig::getThemes() from dynamic to array'],
        ['c' => 'HTMLEditorConfig', 'm' => 'get_active', 'n' => 'Changed return type for method HTMLEditorConfig::get_active() from dynamic to HTMLEditorConfig'],
        ['c' => 'HTMLEditorConfig', 'm' => 'get_active_identifier', 'n' => 'Changed return type for method HTMLEditorConfig::get_active_identifier() from dynamic to string'],
        ['c' => 'HTMLEditorConfig', 'm' => 'get_available_configs_map', 'n' => 'Changed return type for method HTMLEditorConfig::get_available_configs_map() from dynamic to array'],
        ['c' => 'HTMLEditorConfig', 'm' => 'init', 'n' => 'Changed return type for method HTMLEditorConfig::init() from dynamic to void'],
        ['c' => 'HTMLEditorConfig', 'm' => 'setOption', 'n' => 'Changed return type for method HTMLEditorConfig::setOption() from dynamic to HTMLEditorConfig'],
        ['c' => 'HTMLEditorConfig', 'm' => 'setOptions', 'n' => 'Changed return type for method HTMLEditorConfig::setOptions() from dynamic to HTMLEditorConfig'],
        ['c' => 'HTMLEditorConfig', 'm' => 'setThemes', 'n' => 'Changed return type for method HTMLEditorConfig::setThemes() from dynamic to void'],
        ['c' => 'HTMLEditorConfig', 'm' => 'set_active', 'n' => 'Changed return type for method HTMLEditorConfig::set_active() from dynamic to HTMLEditorConfig'],
        ['c' => 'HTMLEditorConfig', 'm' => 'set_active_identifier', 'n' => 'Changed return type for method HTMLEditorConfig::set_active_identifier() from dynamic to void'],
        ['c' => 'HTMLEditorConfig', 'm' => 'set_config', 'n' => 'Changed return type for method HTMLEditorConfig::set_config() from dynamic to HTMLEditorConfig|null'],
        ['c' => 'HTMLValue', 'm' => 'forTemplate', 'n' => 'Changed return type for method HTMLValue::forTemplate() from dynamic to string'],
        ['c' => 'HTMLValue', 'm' => 'getContent', 'n' => 'Changed return type for method HTMLValue::getContent() from dynamic to string'],
        ['c' => 'Member', 'm' => 'password_validator', 'n' => 'Changed return type for method Member::password_validator() from dynamic to PasswordValidator|null'],
        ['c' => 'NumericField', 'm' => 'cast', 'n' => 'Changed return type for method NumericField::cast() from dynamic to mixed'],
        ['c' => 'Relation', 'm' => 'dbObject', 'n' => 'Changed return type for method Relation::dbObject() from dynamic to DBField|null'],
        ['c' => 'SSViewer', 'm' => 'dontRewriteHashlinks', 'n' => 'Changed return type for method SSViewer::dontRewriteHashlinks() from dynamic to SSViewer'],
        ['c' => 'SSViewer', 'm' => 'getRewriteHashLinks', 'n' => 'Changed return type for method SSViewer::getRewriteHashLinks() from dynamic to null|bool|string'],
        ['c' => 'SSViewer', 'm' => 'getRewriteHashLinksDefault', 'n' => 'Changed return type for method SSViewer::getRewriteHashLinksDefault() from dynamic to null|bool|string'],
        ['c' => 'SSViewer', 'm' => 'get_templates_by_class', 'n' => 'Changed return type for method SSViewer::get_templates_by_class() from dynamic to array'],
        ['c' => 'SSViewer', 'm' => 'get_themes', 'n' => 'Changed return type for method SSViewer::get_themes() from dynamic to array'],
        ['c' => 'SSViewer', 'm' => 'process', 'n' => 'Changed return type for method SSViewer::process() from dynamic to DBHTMLText'],
        ['c' => 'SSViewer', 'm' => 'setRewriteHashLinks', 'n' => 'Changed return type for method SSViewer::setRewriteHashLinks() from dynamic to SSViewer'],
        ['c' => 'SSViewer', 'm' => 'set_themes', 'n' => 'Changed return type for method SSViewer::set_themes() from dynamic to void'],
        ['c' => 'SelectionGroup_Item', 'm' => 'getValue', 'n' => 'Changed return type for method SelectionGroup_Item::getValue() from dynamic to mixed'],
        ['c' => 'TaskRunner', 'm' => 'getTasks', 'n' => 'Changed return type for method TaskRunner::getTasks() from dynamic to array'],
        ['c' => 'TimeField', 'm' => 'internalToFrontend', 'n' => 'Changed return type for method TimeField::internalToFrontend() from dynamic to string|null'],
        ['c' => 'TimeField', 'm' => 'tidyInternal', 'n' => 'Changed return type for method TimeField::tidyInternal() from dynamic to string|null'],
        ['c' => 'i18nTextCollectorTask', 'm' => 'getIsMerge', 'n' => 'Changed return type for method i18nTextCollectorTask::getIsMerge() from dynamic to bool'],

        ['c' => 'CanViewPermission', 'm' => 'listPermissionCheck', 'n' => 'Changed return type for method CanViewPermission::listPermissionCheck() from SilverStripe\\ORM\\Filterable to SS_List'],
        ['c' => 'FirstResult', 'm' => 'firstResult', 'n' => 'Changed return type for method FirstResult::firstResult() from SilverStripe\\View\\ViewableData|null to ModelData|null'],

        ['c' => 'Link', 'm' => 'forTemplate', 'n' => 'Changed return type for method Link::forTemplate() from dynamic to string'],

        ['c' => 'SideReportView', 'm' => 'forTemplate', 'n' => 'Changed return type for method SideReportView::forTemplate() from dynamic to string'],

        ['c' => 'SiteConfig', 'm' => 'current_site_config', 'n' => 'Changed return type for method SiteConfig::current_site_config() from dynamic to SiteConfig'],

        ['c' => 'SubsiteXHRController', 'm' => 'SubsiteList', 'n' => 'Changed return type for method SubsiteXHRController::SubsiteList() from dynamic to DBHTMLText'],
        ['c' => 'ThemeResolver', 'm' => 'getThemeList', 'n' => 'Changed return type for method ThemeResolver::getThemeList() from dynamic to array'],

        ['c' => 'Versioned', 'm' => 'get_by_stage', 'n' => 'Changed return type for method Versioned::get_by_stage() from dynamic to DataList'],
        ['c' => 'Versioned', 'm' => 'get_including_deleted', 'n' => 'Changed return type for method Versioned::get_including_deleted() from dynamic to DataList'],

        ['c' => 'WorkflowTemplate', 'm' => 'getDescription', 'n' => 'Changed return type for method WorkflowTemplate::getDescription() from dynamic to string'],
        ['c' => 'WorkflowTemplate', 'm' => 'getName', 'n' => 'Changed return type for method WorkflowTemplate::getName() from dynamic to string'],
        ['c' => 'WorkflowTemplate', 'm' => 'getRemindDays', 'n' => 'Changed return type for method WorkflowTemplate::getRemindDays() from dynamic to int'],
        ['c' => 'WorkflowTemplate', 'm' => 'getSort', 'n' => 'Changed return type for method WorkflowTemplate::getSort() from dynamic to int'],
        ['c' => 'WorkflowTemplate', 'm' => 'getVersion', 'n' => 'Changed return type for method WorkflowTemplate::getVersion() from dynamic to string'],

        ['c' => 'FluentSiteTreeExtension', 'm' => 'updateStatusFlags', 'n' => 'Changed return type for method FluentSiteTreeExtension::updateStatusFlags() from dynamic to void'],
        ['c' => 'LocalDateTime', 'm' => 'setLocalValue', 'n' => 'Changed return type for method LocalDateTime::setLocalValue() from dynamic to LocalDateTime'],




    ];
```
