# Change Parameter Type

I want a custom Rector rule called `ChangedParameterType` for the latest Rector version.

The purpose is to add a TODO upgrade comment when code calls or overrides methods whose parameter type changed, and the existing code likely needs manual review.

## Source data format

For each method signature change, I have config like this:

```php
[
    'c' => 'DNADesign\\Elemental\\Controllers\\ElementalAreaController', // original class name
    'm' => 'formAction', // method name
    'n' => 'Changed parameter type for $output in ElementalAreaController::formAction()', // explanation/note
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

This is a manual review marker. The rule does not need to automatically cast or transform arguments.

Before

```php
$service->run($request);
```

After

```php
/** @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Changed parameter type for $output in BuildTask::run() */
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
/** @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Changed parameter type for $output in BuildTask::run() */
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
@TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Changed parameter type for $output in BuildTask::run()
```

## What I want in the answer

Please provide:

- Full Rector rule class (`ChangedParameterType`)
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
        ['c' => 'AdminRootController', 'm' => 'add_rule_for_controller', 'n' => 'Changed type of parameter $controllerClass in AdminRootController::add_rule_for_controller() from dynamic to string'],
        ['c' => 'LeftAndMain', 'm' => 'jsonError', 'n' => 'Changed type of parameter $errorCode in LeftAndMain::jsonError() from dynamic to int'],
        ['c' => 'LeftAndMain', 'm' => 'jsonError', 'n' => 'Changed type of parameter $errorMessage in LeftAndMain::jsonError() from dynamic to string'],
        ['c' => 'LeftAndMain', 'm' => 'getSchemaResponse', 'n' => 'Changed type of parameter $errors in LeftAndMain::getSchemaResponse() from SilverStripe\\ORM\\ValidationResult to ValidationResult|null'],
        ['c' => 'LeftAndMain', 'm' => 'getSchemaResponse', 'n' => 'Changed type of parameter $extraData in LeftAndMain::getSchemaResponse() from dynamic to array'],
        ['c' => 'LeftAndMain', 'm' => 'getSchemaResponse', 'n' => 'Changed type of parameter $form in LeftAndMain::getSchemaResponse() from dynamic to Form|null'],
        ['c' => 'LeftAndMain', 'm' => 'getSchemaResponse', 'n' => 'Changed type of parameter $schemaID in LeftAndMain::getSchemaResponse() from dynamic to string'],
        ['c' => 'LeftAndMain', 'm' => 'getTemplatesWithSuffix', 'n' => 'Changed type of parameter $suffix in LeftAndMain::getTemplatesWithSuffix() from dynamic to string'],

        ['c' => 'AssetFormFactory', 'm' => 'getFormActions', 'n' => 'Changed type of parameter $controller in AssetFormFactory::getFormActions() from RequestHandler to RequestHandler|null'],
        ['c' => 'AssetFormFactory', 'm' => 'getFormFields', 'n' => 'Changed type of parameter $controller in AssetFormFactory::getFormFields() from RequestHandler to RequestHandler|null'],
        ['c' => 'AssetFormFactory', 'm' => 'getValidator', 'n' => 'Changed type of parameter $controller in AssetFormFactory::getValidator() from RequestHandler to RequestHandler|null'],
        ['c' => 'FileSearchFormFactory', 'm' => 'getFormFields', 'n' => 'Changed type of parameter $controller in FileSearchFormFactory::getFormFields() from RequestHandler to RequestHandler|null'],
        ['c' => 'ThumbnailGenerator', 'm' => 'generateLink', 'n' => 'Changed type of parameter $thumbnail in ThumbnailGenerator::generateLink() from AssetContainer to AssetContainer|null'],

        ['c' => 'Image_Backend', 'm' => '__construct', 'n' => 'Changed type of parameter $assetContainer in Image_Backend::__construct() from AssetContainer to AssetContainer|null'],
        ['c' => 'InterventionBackend', 'm' => 'setAssetContainer', 'n' => 'Changed type of parameter $assetContainer in InterventionBackend::setAssetContainer() from dynamic to AssetContainer|null'],
        ['c' => 'Image_Backend', 'm' => 'paddedResize', 'n' => 'Changed type of parameter $backgroundColor in Image_Backend::paddedResize() from dynamic to string'],
        ['c' => 'InterventionBackend', 'm' => 'setCache', 'n' => 'Changed type of parameter $cache in InterventionBackend::setCache() from dynamic to Psr\\SimpleCache\\CacheInterface'],
        ['c' => 'DBFile', 'm' => 'setAllowedCategories', 'n' => 'Changed type of parameter $categories in DBFile::setAllowedCategories() from dynamic to array|string'],
        ['c' => 'Image_Backend', 'm' => 'writeToStore', 'n' => 'Changed type of parameter $config in Image_Backend::writeToStore() from dynamic to array'],
        ['c' => 'DBFile', 'm' => 'assertFilenameValid', 'n' => 'Changed type of parameter $filename in DBFile::assertFilenameValid() from dynamic to string'],
        ['c' => 'DBFile', 'm' => 'isValidFilename', 'n' => 'Changed type of parameter $filename in DBFile::isValidFilename() from dynamic to string'],
        ['c' => 'DBFile', 'm' => 'validateFilename', 'n' => 'Changed type of parameter $filename in DBFile::validateFilename() from dynamic to string|null'],
        ['c' => 'Image_Backend', 'm' => 'writeToStore', 'n' => 'Changed type of parameter $filename in Image_Backend::writeToStore() from dynamic to string'],
        ['c' => 'DBFile', 'm' => 'getSourceURL', 'n' => 'Changed type of parameter $grant in DBFile::getSourceURL() from dynamic to bool'],
        ['c' => 'Image_Backend', 'm' => 'writeToStore', 'n' => 'Changed type of parameter $hash in Image_Backend::writeToStore() from dynamic to string|null'],
        ['c' => 'InterventionBackend', 'm' => 'getDimensionCacheKey', 'n' => 'Changed type of parameter $hash in InterventionBackend::getDimensionCacheKey() from dynamic to string'],
        ['c' => 'InterventionBackend', 'm' => 'getErrorCacheKey', 'n' => 'Changed type of parameter $hash in InterventionBackend::getErrorCacheKey() from dynamic to string'],
        ['c' => 'InterventionBackend', 'm' => 'hasFailed', 'n' => 'Changed type of parameter $hash in InterventionBackend::hasFailed() from dynamic to string'],
        ['c' => 'InterventionBackend', 'm' => 'markFailed', 'n' => 'Changed type of parameter $hash in InterventionBackend::markFailed() from dynamic to string'],
        ['c' => 'InterventionBackend', 'm' => 'markSuccess', 'n' => 'Changed type of parameter $hash in InterventionBackend::markSuccess() from dynamic to string'],
        ['c' => 'InterventionBackend', 'm' => 'warmCache', 'n' => 'Changed type of parameter $hash in InterventionBackend::warmCache() from dynamic to string'],
        ['c' => 'Image_Backend', 'm' => 'crop', 'n' => 'Changed type of parameter $height in Image_Backend::crop() from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'croppedResize', 'n' => 'Changed type of parameter $height in Image_Backend::croppedResize() from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'paddedResize', 'n' => 'Changed type of parameter $height in Image_Backend::paddedResize() from dynamic to string'],
        ['c' => 'Image_Backend', 'm' => 'resize', 'n' => 'Changed type of parameter $height in Image_Backend::resize() from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'resizeByHeight', 'n' => 'Changed type of parameter $height in Image_Backend::resizeByHeight() from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'resizeRatio', 'n' => 'Changed type of parameter $height in Image_Backend::resizeRatio() from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'crop', 'n' => 'Changed type of parameter $left in Image_Backend::crop() from dynamic to int'],
        ['c' => 'InterventionBackend', 'm' => 'setImageManager', 'n' => 'Changed type of parameter $manager in InterventionBackend::setImageManager() from dynamic to Intervention\\Image\\ImageManager'],
        ['c' => 'LocalFilesystemAdapter', 'm' => '__construct', 'n' => 'Changed type of parameter $mimeTypeDetector in LocalFilesystemAdapter::__construct() from League\\MimeTypeDetection\\MimeTypeDetector to League\\MimeTypeDetection\\MimeTypeDetector|null'],
        ['c' => 'DBFile', 'm' => 'setOriginal', 'n' => 'Changed type of parameter $original in DBFile::setOriginal() from dynamic to AssetContainer'],
        ['c' => 'FileLinkTracking', 'm' => 'setFileParser', 'n' => 'Changed type of parameter $parser in FileLinkTracking::setFileParser() from FileLinkTrackingParser to FileLinkTrackingParser|null'],
        ['c' => 'Filesystem', 'm' => '__construct', 'n' => 'Changed type of parameter $pathNormalizer in Filesystem::__construct() from League\\Flysystem\\PathNormalizer to League\\Flysystem\\PathNormalizer|null'],
        ['c' => 'Image_Backend', 'm' => 'loadFrom', 'n' => 'Changed type of parameter $path in Image_Backend::loadFrom() from dynamic to string'],
        ['c' => 'Image_Backend', 'm' => 'writeTo', 'n' => 'Changed type of parameter $path in Image_Backend::writeTo() from dynamic to string'],
        ['c' => 'InterventionBackend', 'm' => 'setTempPath', 'n' => 'Changed type of parameter $path in InterventionBackend::setTempPath() from dynamic to string'],
        ['c' => 'Image_Backend', 'm' => 'setQuality', 'n' => 'Changed type of parameter $quality in Image_Backend::setQuality() from dynamic to int'],
        ['c' => 'InterventionBackend', 'm' => 'markFailed', 'n' => 'Changed type of parameter $reason in InterventionBackend::markFailed() from dynamic to string'],
        ['c' => 'InterventionBackend', 'm' => 'getResourceDimensions', 'n' => 'Changed type of parameter $resource in InterventionBackend::getResourceDimensions() from Intervention\\Image\\Image to Intervention\\Image\\Interfaces\\ImageInterface'],
        ['c' => 'InterventionBackend', 'm' => 'isStreamReadable', 'n' => 'Changed type of parameter $stream in InterventionBackend::isStreamReadable() from dynamic to mixed'],
        ['c' => 'Image_Backend', 'm' => 'crop', 'n' => 'Changed type of parameter $top in Image_Backend::crop() from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'paddedResize', 'n' => 'Changed type of parameter $transparencyPercent in Image_Backend::paddedResize() from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'resizeRatio', 'n' => 'Changed type of parameter $useAsMinimum in Image_Backend::resizeRatio() from dynamic to bool'],
        ['c' => 'Image_Backend', 'm' => 'writeToStore', 'n' => 'Changed type of parameter $variant in Image_Backend::writeToStore() from dynamic to string|null'],
        ['c' => 'InterventionBackend', 'm' => 'getDimensionCacheKey', 'n' => 'Changed type of parameter $variant in InterventionBackend::getDimensionCacheKey() from dynamic to string|null'],
        ['c' => 'InterventionBackend', 'm' => 'getErrorCacheKey', 'n' => 'Changed type of parameter $variant in InterventionBackend::getErrorCacheKey() from dynamic to string|null'],
        ['c' => 'InterventionBackend', 'm' => 'hasFailed', 'n' => 'Changed type of parameter $variant in InterventionBackend::hasFailed() from dynamic to string|null'],
        ['c' => 'InterventionBackend', 'm' => 'markFailed', 'n' => 'Changed type of parameter $variant in InterventionBackend::markFailed() from dynamic to string|null'],
        ['c' => 'InterventionBackend', 'm' => 'markSuccess', 'n' => 'Changed type of parameter $variant in InterventionBackend::markSuccess() from dynamic to string|null'],
        ['c' => 'InterventionBackend', 'm' => 'warmCache', 'n' => 'Changed type of parameter $variant in InterventionBackend::warmCache() from dynamic to string|null'],
        ['c' => 'LocalFilesystemAdapter', 'm' => '__construct', 'n' => 'Changed type of parameter $visibility in LocalFilesystemAdapter::__construct() from League\\Flysystem\\UnixVisibility\\VisibilityConverter to League\\Flysystem\\UnixVisibility\\VisibilityConverter|null'],
        ['c' => 'Image_Backend', 'm' => 'crop', 'n' => 'Changed type of parameter $width in Image_Backend::crop() from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'croppedResize', 'n' => 'Changed type of parameter $width in Image_Backend::croppedResize() from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'paddedResize', 'n' => 'Changed type of parameter $width in Image_Backend::paddedResize() from dynamic to string'],
        ['c' => 'Image_Backend', 'm' => 'resize', 'n' => 'Changed type of parameter $width in Image_Backend::resize() from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'resizeByWidth', 'n' => 'Changed type of parameter $width in Image_Backend::resizeByWidth() from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'resizeRatio', 'n' => 'Changed type of parameter $width in Image_Backend::resizeRatio() from dynamic to int'],

        ['c' => 'BlogPostFilter', 'm' => 'augmentLoadLazyFields', 'n' => 'Changed type of parameter $dataQuery in BlogPostFilter::augmentLoadLazyFields() from DataQuery to DataQuery|null'],

        ['c' => 'SiteTree', 'm' => 'getStatusFlags', 'n' => 'Changed type of parameter $cached in SiteTree::getStatusFlags() from dynamic to bool'],
        ['c' => 'VirtualPage', 'm' => '__get', 'n' => 'Changed type of parameter $field in VirtualPage::__get() from dynamic to string'],
        ['c' => 'VirtualPage', 'm' => 'castingHelper', 'n' => 'Changed type of parameter $field in VirtualPage::castingHelper() from dynamic to string'],
        ['c' => 'VirtualPage', 'm' => 'getField', 'n' => 'Changed type of parameter $field in VirtualPage::getField() from dynamic to string'],
        ['c' => 'VirtualPage', 'm' => 'hasField', 'n' => 'Changed type of parameter $field in VirtualPage::hasField() from dynamic to string'],
        ['c' => 'SiteTree', 'm' => 'canAddChildren', 'n' => 'Changed type of parameter $member in SiteTree::canAddChildren() from dynamic to Member|null'],
        ['c' => 'CMSMain', 'm' => 'getTreeNodeClasses', 'n' => 'Changed type of parameter $node in CMSMain::getTreeNodeClasses() from SiteTree to DataObject'],
        ['c' => 'CMSMain', 'm' => 'getCMSEditLinkForManagedDataObject', 'n' => 'Changed type of parameter $obj in CMSMain::getCMSEditLinkForManagedDataObject() from SiteTree to DataObject'],
        ['c' => 'SiteTreeLinkTracking', 'm' => 'setParser', 'n' => 'Changed type of parameter $parser in SiteTreeLinkTracking::setParser() from SiteTreeLinkTracking_Parser to SiteTreeLinkTracking_Parser|null'],
        ['c' => 'CMSMain', 'm' => 'getArchiveWarningMessage', 'n' => 'Changed type of parameter $record in CMSMain::getArchiveWarningMessage() from dynamic to DataObject'],
        ['c' => 'VirtualPage', 'm' => 'getViewerTemplates', 'n' => 'Changed type of parameter $suffix in VirtualPage::getViewerTemplates() from dynamic to string'],

        ['c' => 'DBString', 'm' => 'LimitCharacters', 'n' => 'Changed type of parameter $add in DBString::LimitCharacters() from dynamic to string|false'],
        ['c' => 'DBString', 'm' => 'LimitCharactersToClosestWord', 'n' => 'Changed type of parameter $add in DBString::LimitCharactersToClosestWord() from dynamic to string|false'],
        ['c' => 'DBString', 'm' => 'LimitWordCount', 'n' => 'Changed type of parameter $add in DBString::LimitWordCount() from dynamic to string|false'],
        ['c' => 'DBText', 'm' => 'Summary', 'n' => 'Changed type of parameter $add in DBText::Summary() from dynamic to string|false'],
        ['c' => 'DBMoney', 'm' => 'setAmount', 'n' => 'Changed type of parameter $amount in DBMoney::setAmount() from dynamic to mixed'],
        ['c' => 'DBField', 'm' => 'create_field', 'n' => 'Changed type of parameter $args in DBField::create_field() from dynamic to mixed'],
        ['c' => 'SSViewer', 'm' => 'process', 'n' => 'Changed type of parameter $arguments in SSViewer::process() from dynamic to array'],
        ['c' => 'DBPrimaryKey', 'm' => 'setAutoIncrement', 'n' => 'Changed type of parameter $autoIncrement in DBPrimaryKey::setAutoIncrement() from dynamic to bool'],
        ['c' => 'DBClassNameTrait', 'm' => '__construct', 'n' => 'Changed type of parameter $baseClass in DBClassNameTrait::__construct() from dynamic to string|null'],
        ['c' => 'DBClassNameTrait', 'm' => 'setBaseClass', 'n' => 'Changed type of parameter $baseClass in DBClassNameTrait::setBaseClass() from dynamic to string|null'],
        ['c' => 'SSViewer', 'm' => 'get_templates_by_class', 'n' => 'Changed type of parameter $baseClass in SSViewer::get_templates_by_class() from dynamic to string|null'],
        ['c' => 'Email', 'm' => 'setBody', 'n' => 'Changed type of parameter $body in Email::setBody() from Symfony\\Component\\Mime\\Part\\AbstractPart|string to Symfony\\Component\\Mime\\Part\\AbstractPart|string|null'],
        ['c' => 'ClassManifest', 'm' => '__construct', 'n' => 'Changed type of parameter $cacheFactory in ClassManifest::__construct() from CacheFactory to CacheFactory|null'],
        ['c' => 'CoreConfigFactory', 'm' => '__construct', 'n' => 'Changed type of parameter $cacheFactory in CoreConfigFactory::__construct() from CacheFactory to CacheFactory|null'],
        ['c' => 'ModuleManifest', 'm' => '__construct', 'n' => 'Changed type of parameter $cacheFactory in ModuleManifest::__construct() from CacheFactory to CacheFactory|null'],
        ['c' => 'ThemeManifest', 'm' => '__construct', 'n' => 'Changed type of parameter $cacheFactory in ThemeManifest::__construct() from CacheFactory to CacheFactory|null'],
        ['c' => 'InheritedPermissions', 'm' => '__construct', 'n' => 'Changed type of parameter $cache in InheritedPermissions::__construct() from Psr\\SimpleCache\\CacheInterface to Psr\\SimpleCache\\CacheInterface|null'],
        ['c' => 'DBDatetime', 'm' => 'withFixedNow', 'n' => 'Changed type of parameter $callback in DBDatetime::withFixedNow() from dynamic to callable'],
        ['c' => 'Form', 'm' => 'sessionError', 'n' => 'Changed type of parameter $cast in Form::sessionError() from dynamic to string'],
        ['c' => 'Form', 'm' => 'sessionFieldError', 'n' => 'Changed type of parameter $cast in Form::sessionFieldError() from dynamic to string'],
        ['c' => 'Form', 'm' => 'sessionMessage', 'n' => 'Changed type of parameter $cast in Form::sessionMessage() from dynamic to string'],
        ['c' => 'DBText', 'm' => 'ContextSummary', 'n' => 'Changed type of parameter $characters in DBText::ContextSummary() from dynamic to int'],
        ['c' => 'SSViewer', 'm' => 'get_templates_by_class', 'n' => 'Changed type of parameter $classOrObject in SSViewer::get_templates_by_class() from dynamic to string|object'],
        ['c' => 'HTTP', 'm' => 'urlRewriter', 'n' => 'Changed type of parameter $code in HTTP::urlRewriter() from dynamic to callable'],
        ['c' => 'DataList', 'm' => 'columnUnique', 'n' => 'Changed type of parameter $colName in DataList::columnUnique() from dynamic to string'],
        ['c' => 'EagerLoadedList', 'm' => 'columnUnique', 'n' => 'Changed type of parameter $colName in EagerLoadedList::columnUnique() from dynamic to string'],
        ['c' => 'HTMLEditorConfig', 'm' => 'set_config', 'n' => 'Changed type of parameter $config in HTMLEditorConfig::set_config() from HTMLEditorConfig to HTMLEditorConfig|null'],
        ['c' => 'Form', 'm' => 'setController', 'n' => 'Changed type of parameter $controller in Form::setController() from RequestHandler to RequestHandler|null'],
        ['c' => 'FormFactory', 'm' => 'getForm', 'n' => 'Changed type of parameter $controller in FormFactory::getForm() from RequestHandler to RequestHandler|null'],
        ['c' => 'Cookie_Backend', 'm' => '__construct', 'n' => 'Changed type of parameter $cookies in Cookie_Backend::__construct() from dynamic to array'],
        ['c' => 'DBMoney', 'm' => 'setCurrency', 'n' => 'Changed type of parameter $currency in DBMoney::setCurrency() from dynamic to string|null'],
        ['c' => 'FieldList', 'm' => 'removeByName', 'n' => 'Changed type of parameter $dataFieldOnly in FieldList::removeByName() from dynamic to bool'],
        ['c' => 'FieldList', 'm' => 'replaceField', 'n' => 'Changed type of parameter $dataFieldOnly in FieldList::replaceField() from dynamic to bool'],
        ['c' => 'DBComposite', 'm' => 'bindTo', 'n' => 'Changed type of parameter $dataObject in DBComposite::bindTo() from dynamic to DataObject'],
        ['c' => 'DBField', 'm' => 'saveInto', 'n' => 'Changed type of parameter $dataObject in DBField::saveInto() from dynamic to ModelData'],
        ['c' => 'Email', 'm' => 'setData', 'n' => 'Changed type of parameter $data in Email::setData() from array|SilverStripe\\View\\ViewableData to array|ModelData'],
        ['c' => 'FieldList', 'm' => 'setValues', 'n' => 'Changed type of parameter $data in FieldList::setValues() from dynamic to array'],
        ['c' => 'Form', 'm' => 'loadDataFrom', 'n' => 'Changed type of parameter $data in Form::loadDataFrom() from dynamic to object|array'],
        ['c' => 'DBDate', 'm' => 'getCustomFormatter', 'n' => 'Changed type of parameter $dateLength in DBDate::getCustomFormatter() from dynamic to int'],
        ['c' => 'DBDate', 'm' => 'getFormatter', 'n' => 'Changed type of parameter $dateLength in DBDate::getFormatter() from dynamic to int'],
        ['c' => 'DateField', 'm' => 'internalToFrontend', 'n' => 'Changed type of parameter $date in DateField::internalToFrontend() from dynamic to mixed'],
        ['c' => 'DateField', 'm' => 'tidyInternal', 'n' => 'Changed type of parameter $date in DateField::tidyInternal() from dynamic to mixed'],
        ['c' => 'DBDatetime', 'm' => 'set_mock_now', 'n' => 'Changed type of parameter $datetime in DBDatetime::set_mock_now() from dynamic to DBDatetime|string'],
        ['c' => 'DatetimeField', 'm' => 'internalToFrontend', 'n' => 'Changed type of parameter $datetime in DatetimeField::internalToFrontend() from dynamic to mixed'],
        ['c' => 'DatetimeField', 'm' => 'tidyInternal', 'n' => 'Changed type of parameter $datetime in DatetimeField::tidyInternal() from dynamic to mixed'],
        ['c' => 'DBField', 'm' => 'setDefaultValue', 'n' => 'Changed type of parameter $defaultValue in DBField::setDefaultValue() from dynamic to mixed'],
        ['c' => 'DBEnum', 'm' => 'setDefault', 'n' => 'Changed type of parameter $default in DBEnum::setDefault() from dynamic to string|null'],
        ['c' => 'CookieAuthenticationHandler', 'm' => 'setDeviceCookieName', 'n' => 'Changed type of parameter $deviceCookieName in CookieAuthenticationHandler::setDeviceCookieName() from dynamic to string'],
        ['c' => 'Cookie', 'm' => 'force_expiry', 'n' => 'Changed type of parameter $domain in Cookie::force_expiry() from dynamic to string|null'],
        ['c' => 'Cookie', 'm' => 'set', 'n' => 'Changed type of parameter $domain in Cookie::set() from dynamic to string|null'],
        ['c' => 'CookieJar', 'm' => 'outputCookie', 'n' => 'Changed type of parameter $domain in CookieJar::outputCookie() from dynamic to string|null'],
        ['c' => 'Cookie_Backend', 'm' => 'forceExpiry', 'n' => 'Changed type of parameter $domain in Cookie_Backend::forceExpiry() from dynamic to string|null'],
        ['c' => 'Cookie_Backend', 'm' => 'set', 'n' => 'Changed type of parameter $domain in Cookie_Backend::set() from dynamic to string|null'],
        ['c' => 'DBEnum', 'm' => 'formField', 'n' => 'Changed type of parameter $emptyString in DBEnum::formField() from dynamic to string|null'],
        ['c' => 'DBEnum', 'm' => 'setEnum', 'n' => 'Changed type of parameter $enum in DBEnum::setEnum() from dynamic to string|array'],
        ['c' => 'TestMailer', 'm' => 'send', 'n' => 'Changed type of parameter $envelope in TestMailer::send() from Symfony\\Component\\Mailer\\Envelope to Symfony\\Component\\Mailer\\Envelope|null'],
        ['c' => 'Cookie', 'm' => 'set', 'n' => 'Changed type of parameter $expiry in Cookie::set() from dynamic to int|float'],
        ['c' => 'CookieJar', 'm' => 'outputCookie', 'n' => 'Changed type of parameter $expiry in CookieJar::outputCookie() from dynamic to int'],
        ['c' => 'Cookie_Backend', 'm' => 'set', 'n' => 'Changed type of parameter $expiry in Cookie_Backend::set() from dynamic to int|float'],
        ['c' => 'Form', 'm' => 'loadDataFrom', 'n' => 'Changed type of parameter $fieldList in Form::loadDataFrom() from dynamic to array'],
        ['c' => 'DataList', 'm' => 'dbObject', 'n' => 'Changed type of parameter $fieldName in DataList::dbObject() from dynamic to string'],
        ['c' => 'DataObject', 'm' => 'dbObject', 'n' => 'Changed type of parameter $fieldName in DataObject::dbObject() from dynamic to string'],
        ['c' => 'DataObjectInterface', 'm' => '__get', 'n' => 'Changed type of parameter $fieldName in DataObjectInterface::__get() from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'removeByName', 'n' => 'Changed type of parameter $fieldName in FieldList::removeByName() from dynamic to string|array'],
        ['c' => 'FieldList', 'm' => 'removeFieldFromTab', 'n' => 'Changed type of parameter $fieldName in FieldList::removeFieldFromTab() from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'renameField', 'n' => 'Changed type of parameter $fieldName in FieldList::renameField() from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'replaceField', 'n' => 'Changed type of parameter $fieldName in FieldList::replaceField() from dynamic to string'],
        ['c' => 'Form', 'm' => 'sessionFieldError', 'n' => 'Changed type of parameter $fieldName in Form::sessionFieldError() from dynamic to string'],
        ['c' => 'Relation', 'm' => 'dbObject', 'n' => 'Changed type of parameter $fieldName in Relation::dbObject() from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'changeFieldOrder', 'n' => 'Changed type of parameter $fieldNames in FieldList::changeFieldOrder() from dynamic to array|string'],
        ['c' => 'DBComposite', 'm' => 'dbObject', 'n' => 'Changed type of parameter $field in DBComposite::dbObject() from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'addFieldToTab', 'n' => 'Changed type of parameter $field in FieldList::addFieldToTab() from dynamic to FormField'],
        ['c' => 'FieldList', 'm' => 'fieldPosition', 'n' => 'Changed type of parameter $field in FieldList::fieldPosition() from dynamic to string|FormField'],
        ['c' => 'FieldList', 'm' => 'makeFieldReadonly', 'n' => 'Changed type of parameter $field in FieldList::makeFieldReadonly() from dynamic to string|array|FormField'],
        ['c' => 'FieldList', 'm' => 'setContainerField', 'n' => 'Changed type of parameter $field in FieldList::setContainerField() from dynamic to CompositeField|null'],
        ['c' => 'FieldList', 'm' => 'addFieldsToTab', 'n' => 'Changed type of parameter $fields in FieldList::addFieldsToTab() from dynamic to array'],
        ['c' => 'FieldList', 'm' => 'removeFieldsFromTab', 'n' => 'Changed type of parameter $fields in FieldList::removeFieldsFromTab() from dynamic to array'],
        ['c' => 'FieldList', 'm' => 'setForm', 'n' => 'Changed type of parameter $form in FieldList::setForm() from dynamic to Form'],
        ['c' => 'FormSchema', 'm' => 'getMultipartSchema', 'n' => 'Changed type of parameter $form in FormSchema::getMultipartSchema() from Form to Form|null'],
        ['c' => 'DBDate', 'm' => 'Format', 'n' => 'Changed type of parameter $format in DBDate::Format() from dynamic to string'],
        ['c' => 'DBTime', 'm' => 'Format', 'n' => 'Changed type of parameter $format in DBTime::Format() from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'fieldNameError', 'n' => 'Changed type of parameter $functionName in FieldList::fieldNameError() from dynamic to string'],
        ['c' => 'DBEnum', 'm' => 'enumValues', 'n' => 'Changed type of parameter $hasEmpty in DBEnum::enumValues() from dynamic to bool'],
        ['c' => 'DBEnum', 'm' => 'formField', 'n' => 'Changed type of parameter $hasEmpty in DBEnum::formField() from dynamic to bool'],
        ['c' => 'DBText', 'm' => 'ContextSummary', 'n' => 'Changed type of parameter $highlight in DBText::ContextSummary() from dynamic to bool'],
        ['c' => 'Cookie', 'm' => 'force_expiry', 'n' => 'Changed type of parameter $httpOnly in Cookie::force_expiry() from dynamic to bool'],
        ['c' => 'Cookie', 'm' => 'set', 'n' => 'Changed type of parameter $httpOnly in Cookie::set() from dynamic to bool'],
        ['c' => 'CookieJar', 'm' => 'outputCookie', 'n' => 'Changed type of parameter $httpOnly in CookieJar::outputCookie() from dynamic to bool'],
        ['c' => 'Cookie_Backend', 'm' => 'forceExpiry', 'n' => 'Changed type of parameter $httpOnly in Cookie_Backend::forceExpiry() from dynamic to bool'],
        ['c' => 'Cookie_Backend', 'm' => 'set', 'n' => 'Changed type of parameter $httpOnly in Cookie_Backend::set() from dynamic to bool'],
        ['c' => 'HTMLEditorConfig', 'm' => 'set_active_identifier', 'n' => 'Changed type of parameter $identifier in HTMLEditorConfig::set_active_identifier() from dynamic to string'],
        ['c' => 'HTMLEditorConfig', 'm' => 'set_config', 'n' => 'Changed type of parameter $identifier in HTMLEditorConfig::set_config() from dynamic to string'],
        ['c' => 'SSViewer', 'm' => 'includeRequirements', 'n' => 'Changed type of parameter $incl in SSViewer::includeRequirements() from dynamic to bool'],
        ['c' => 'DBDate', 'm' => 'DayOfMonth', 'n' => 'Changed type of parameter $includeOrdinal in DBDate::DayOfMonth() from dynamic to bool'],
        ['c' => 'Cookie', 'm' => 'get', 'n' => 'Changed type of parameter $includeUnsent in Cookie::get() from dynamic to bool'],
        ['c' => 'Cookie', 'm' => 'get_all', 'n' => 'Changed type of parameter $includeUnsent in Cookie::get_all() from dynamic to bool'],
        ['c' => 'Cookie_Backend', 'm' => 'get', 'n' => 'Changed type of parameter $includeUnsent in Cookie_Backend::get() from dynamic to bool'],
        ['c' => 'Cookie_Backend', 'm' => 'getAll', 'n' => 'Changed type of parameter $includeUnsent in Cookie_Backend::getAll() from dynamic to bool'],
        ['c' => 'FieldList', 'm' => 'addFieldToTab', 'n' => 'Changed type of parameter $insertBefore in FieldList::addFieldToTab() from dynamic to string|null'],
        ['c' => 'FieldList', 'm' => 'addFieldsToTab', 'n' => 'Changed type of parameter $insertBefore in FieldList::addFieldsToTab() from dynamic to string|null'],
        ['c' => 'SSListContains', 'm' => 'checkIfItemEvaluatesRemainingMatches', 'n' => 'Changed type of parameter $item in SSListContains::checkIfItemEvaluatesRemainingMatches() from SilverStripe\\View\\ViewableData to ModelData'],
        ['c' => 'SSViewer', 'm' => 'process', 'n' => 'Changed type of parameter $item in SSViewer::process() from dynamic to mixed'],
        ['c' => 'HTMLEditorConfig', 'm' => 'getOption', 'n' => 'Changed type of parameter $key in HTMLEditorConfig::getOption() from dynamic to string'],
        ['c' => 'HTMLEditorConfig', 'm' => 'setOption', 'n' => 'Changed type of parameter $key in HTMLEditorConfig::setOption() from dynamic to string'],
        ['c' => 'DBText', 'm' => 'ContextSummary', 'n' => 'Changed type of parameter $keywords in DBText::ContextSummary() from dynamic to string|null'],
        ['c' => 'DBString', 'm' => 'LimitCharacters', 'n' => 'Changed type of parameter $limit in DBString::LimitCharacters() from dynamic to int'],
        ['c' => 'DBString', 'm' => 'LimitCharactersToClosestWord', 'n' => 'Changed type of parameter $limit in DBString::LimitCharactersToClosestWord() from dynamic to int'],
        ['c' => 'SearchContext', 'm' => 'getQuery', 'n' => 'Changed type of parameter $limit in SearchContext::getQuery() from dynamic to int|array|null'],
        ['c' => 'DBDate', 'm' => 'getCustomFormatter', 'n' => 'Changed type of parameter $locale in DBDate::getCustomFormatter() from dynamic to string|null'],
        ['c' => 'DBMoney', 'm' => 'setLocale', 'n' => 'Changed type of parameter $locale in DBMoney::setLocale() from dynamic to string'],
        ['c' => 'DefaultCacheFactory', 'm' => '__construct', 'n' => 'Changed type of parameter $logger in DefaultCacheFactory::__construct() from Psr\\Log\\LoggerInterface to Psr\\Log\\LoggerInterface|null'],
        ['c' => 'DBField', 'm' => 'writeToManipulation', 'n' => 'Changed type of parameter $manipulation in DBField::writeToManipulation() from dynamic to array'],
        ['c' => 'DBClassNameTrait', 'm' => 'setValue', 'n' => 'Changed type of parameter $markChanged in DBClassNameTrait::setValue() from dynamic to bool'],
        ['c' => 'DBField', 'm' => 'setValue', 'n' => 'Changed type of parameter $markChanged in DBField::setValue() from dynamic to bool'],
        ['c' => 'DBMoney', 'm' => 'setAmount', 'n' => 'Changed type of parameter $markChanged in DBMoney::setAmount() from dynamic to bool'],
        ['c' => 'DBMoney', 'm' => 'setCurrency', 'n' => 'Changed type of parameter $markChanged in DBMoney::setCurrency() from dynamic to bool'],
        ['c' => 'DBPolymorphicForeignKey', 'm' => 'setClassValue', 'n' => 'Changed type of parameter $markChanged in DBPolymorphicForeignKey::setClassValue() from dynamic to bool'],
        ['c' => 'DBPolymorphicForeignKey', 'm' => 'setIDValue', 'n' => 'Changed type of parameter $markChanged in DBPolymorphicForeignKey::setIDValue() from dynamic to bool'],
        ['c' => 'ConfirmedPasswordField', 'm' => 'setMaxLength', 'n' => 'Changed type of parameter $maxLength in ConfirmedPasswordField::setMaxLength() from dynamic to int'],
        ['c' => 'DBText', 'm' => 'LimitSentences', 'n' => 'Changed type of parameter $maxSentences in DBText::LimitSentences() from dynamic to int'],
        ['c' => 'DBText', 'm' => 'Summary', 'n' => 'Changed type of parameter $maxWords in DBText::Summary() from dynamic to int'],
        ['c' => 'WithinRangeFilter', 'm' => 'setMax', 'n' => 'Changed type of parameter $max in WithinRangeFilter::setMax() from dynamic to mixed'],
        ['c' => 'ChangePasswordHandler', 'm' => 'setSessionToken', 'n' => 'Changed type of parameter $member in ChangePasswordHandler::setSessionToken() from dynamic to Member'],
        ['c' => 'DBDate', 'm' => 'FormatFromSettings', 'n' => 'Changed type of parameter $member in DBDate::FormatFromSettings() from dynamic to Member|null'],
        ['c' => 'DBTime', 'm' => 'FormatFromSettings', 'n' => 'Changed type of parameter $member in DBTime::FormatFromSettings() from dynamic to Member|null'],
        ['c' => 'DefaultPermissionChecker', 'm' => 'canCreate', 'n' => 'Changed type of parameter $member in DefaultPermissionChecker::canCreate() from Member to Member|null'],
        ['c' => 'DefaultPermissionChecker', 'm' => 'canDelete', 'n' => 'Changed type of parameter $member in DefaultPermissionChecker::canDelete() from Member to Member|null'],
        ['c' => 'DefaultPermissionChecker', 'm' => 'canEdit', 'n' => 'Changed type of parameter $member in DefaultPermissionChecker::canEdit() from Member to Member|null'],
        ['c' => 'DefaultPermissionChecker', 'm' => 'canView', 'n' => 'Changed type of parameter $member in DefaultPermissionChecker::canView() from Member to Member|null'],
        ['c' => 'InheritedPermissions', 'm' => 'batchPermissionCheck', 'n' => 'Changed type of parameter $member in InheritedPermissions::batchPermissionCheck() from Member to Member|null'],
        ['c' => 'InheritedPermissions', 'm' => 'batchPermissionCheckForStage', 'n' => 'Changed type of parameter $member in InheritedPermissions::batchPermissionCheckForStage() from Member to Member|null'],
        ['c' => 'InheritedPermissions', 'm' => 'checkDefaultPermissions', 'n' => 'Changed type of parameter $member in InheritedPermissions::checkDefaultPermissions() from Member to Member|null'],
        ['c' => 'MemberAuthenticator', 'm' => 'authenticateMember', 'n' => 'Changed type of parameter $member in MemberAuthenticator::authenticateMember() from Member to Member|null'],
        ['c' => 'PermissionChecker', 'm' => 'canDelete', 'n' => 'Changed type of parameter $member in PermissionChecker::canDelete() from Member to Member|null'],
        ['c' => 'PermissionChecker', 'm' => 'canDeleteMultiple', 'n' => 'Changed type of parameter $member in PermissionChecker::canDeleteMultiple() from Member to Member|null'],
        ['c' => 'PermissionChecker', 'm' => 'canEdit', 'n' => 'Changed type of parameter $member in PermissionChecker::canEdit() from Member to Member|null'],
        ['c' => 'PermissionChecker', 'm' => 'canEditMultiple', 'n' => 'Changed type of parameter $member in PermissionChecker::canEditMultiple() from Member to Member|null'],
        ['c' => 'PermissionChecker', 'm' => 'canView', 'n' => 'Changed type of parameter $member in PermissionChecker::canView() from Member to Member|null'],
        ['c' => 'PermissionChecker', 'm' => 'canViewMultiple', 'n' => 'Changed type of parameter $member in PermissionChecker::canViewMultiple() from Member to Member|null'],
        ['c' => 'MemcachedCacheFactory', 'm' => '__construct', 'n' => 'Changed type of parameter $memcachedClient in MemcachedCacheFactory::__construct() from Memcached to Psr\\Log\\LoggerInterface|null'],
        ['c' => 'Form', 'm' => 'loadDataFrom', 'n' => 'Changed type of parameter $mergeStrategy in Form::loadDataFrom() from dynamic to int'],
        ['c' => 'Form', 'm' => 'sessionError', 'n' => 'Changed type of parameter $message in Form::sessionError() from dynamic to string'],
        ['c' => 'Form', 'm' => 'sessionFieldError', 'n' => 'Changed type of parameter $message in Form::sessionFieldError() from dynamic to string'],
        ['c' => 'Form', 'm' => 'sessionMessage', 'n' => 'Changed type of parameter $message in Form::sessionMessage() from dynamic to string'],
        ['c' => 'ConfirmedPasswordField', 'm' => 'setMinLength', 'n' => 'Changed type of parameter $minLength in ConfirmedPasswordField::setMinLength() from dynamic to int'],
        ['c' => 'WithinRangeFilter', 'm' => 'setMin', 'n' => 'Changed type of parameter $min in WithinRangeFilter::setMin() from dynamic to mixed'],
        ['c' => 'i18nTextCollector', 'm' => 'collectFromEntityProviders', 'n' => 'Changed type of parameter $module in i18nTextCollector::collectFromEntityProviders() from Module to Module|null'],
        ['c' => 'Cookie', 'm' => 'force_expiry', 'n' => 'Changed type of parameter $name in Cookie::force_expiry() from dynamic to string'],
        ['c' => 'Cookie', 'm' => 'get', 'n' => 'Changed type of parameter $name in Cookie::get() from dynamic to string'],
        ['c' => 'Cookie', 'm' => 'set', 'n' => 'Changed type of parameter $name in Cookie::set() from dynamic to string'],
        ['c' => 'CookieJar', 'm' => 'outputCookie', 'n' => 'Changed type of parameter $name in CookieJar::outputCookie() from dynamic to string'],
        ['c' => 'Cookie_Backend', 'm' => 'forceExpiry', 'n' => 'Changed type of parameter $name in Cookie_Backend::forceExpiry() from dynamic to string'],
        ['c' => 'Cookie_Backend', 'm' => 'get', 'n' => 'Changed type of parameter $name in Cookie_Backend::get() from dynamic to string'],
        ['c' => 'Cookie_Backend', 'm' => 'set', 'n' => 'Changed type of parameter $name in Cookie_Backend::set() from dynamic to string'],
        ['c' => 'DBClassNameTrait', 'm' => '__construct', 'n' => 'Changed type of parameter $name in DBClassNameTrait::__construct() from dynamic to string|null'],
        ['c' => 'DBEnum', 'm' => 'formField', 'n' => 'Changed type of parameter $name in DBEnum::formField() from dynamic to string|null'],
        ['c' => 'DBField', 'm' => 'create_field', 'n' => 'Changed type of parameter $name in DBField::create_field() from dynamic to string|null'],
        ['c' => 'DBField', 'm' => 'defaultSearchFilter', 'n' => 'Changed type of parameter $name in DBField::defaultSearchFilter() from dynamic to string|null'],
        ['c' => 'DBField', 'm' => 'setName', 'n' => 'Changed type of parameter $name in DBField::setName() from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'dataFieldByName', 'n' => 'Changed type of parameter $name in FieldList::dataFieldByName() from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'fieldByName', 'n' => 'Changed type of parameter $name in FieldList::fieldByName() from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'renameField', 'n' => 'Changed type of parameter $newFieldTitle in FieldList::renameField() from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'replaceField', 'n' => 'Changed type of parameter $newField in FieldList::replaceField() from dynamic to FormField'],
        ['c' => 'DBString', 'm' => 'LimitWordCount', 'n' => 'Changed type of parameter $numWords in DBString::LimitWordCount() from dynamic to int'],
        ['c' => 'DBClassNameTrait', 'm' => '__construct', 'n' => 'Changed type of parameter $options in DBClassNameTrait::__construct() from dynamic to array'],
        ['c' => 'HTMLEditorConfig', 'm' => 'setOptions', 'n' => 'Changed type of parameter $options in HTMLEditorConfig::setOptions() from dynamic to array'],
        ['c' => 'DBField', 'm' => 'scaffoldFormField', 'n' => 'Changed type of parameter $params in DBField::scaffoldFormField() from dynamic to array'],
        ['c' => 'SSViewer', 'm' => '__construct', 'n' => 'Changed type of parameter $parser in SSViewer::__construct() from SilverStripe\\View\\TemplateParser to TemplateEngine|null'],
        ['c' => 'Cookie', 'm' => 'force_expiry', 'n' => 'Changed type of parameter $path in Cookie::force_expiry() from dynamic to string|null'],
        ['c' => 'Cookie', 'm' => 'set', 'n' => 'Changed type of parameter $path in Cookie::set() from dynamic to string|null'],
        ['c' => 'CookieJar', 'm' => 'outputCookie', 'n' => 'Changed type of parameter $path in CookieJar::outputCookie() from dynamic to string|null'],
        ['c' => 'Cookie_Backend', 'm' => 'forceExpiry', 'n' => 'Changed type of parameter $path in Cookie_Backend::forceExpiry() from dynamic to string|null'],
        ['c' => 'Cookie_Backend', 'm' => 'set', 'n' => 'Changed type of parameter $path in Cookie_Backend::set() from dynamic to string|null'],
        ['c' => 'DBDate', 'm' => 'getCustomFormatter', 'n' => 'Changed type of parameter $pattern in DBDate::getCustomFormatter() from dynamic to string|null'],
        ['c' => 'DataObject', 'm' => 'flushCache', 'n' => 'Changed type of parameter $persistent in DataObject::flushCache() from dynamic to bool'],
        ['c' => 'DBText', 'm' => 'ContextSummary', 'n' => 'Changed type of parameter $prefix in DBText::ContextSummary() from dynamic to string|false'],
        ['c' => 'DBHTMLText', 'm' => 'setProcessShortcodes', 'n' => 'Changed type of parameter $process in DBHTMLText::setProcessShortcodes() from dynamic to bool'],
        ['c' => 'DBHTMLVarchar', 'm' => 'setProcessShortcodes', 'n' => 'Changed type of parameter $process in DBHTMLVarchar::setProcessShortcodes() from dynamic to bool'],
        ['c' => 'DBField', 'm' => 'addToQuery', 'n' => 'Changed type of parameter $query in DBField::addToQuery() from dynamic to SQLSelect'],
        ['c' => 'DBClassNameTrait', 'm' => 'setValue', 'n' => 'Changed type of parameter $record in DBClassNameTrait::setValue() from dynamic to null|array|ModelData'],
        ['c' => 'DBField', 'm' => 'setValue', 'n' => 'Changed type of parameter $record in DBField::setValue() from dynamic to null|array|ModelData'],
        ['c' => 'BasicAuth', 'm' => 'protect_site_if_necessary', 'n' => 'Changed type of parameter $request in BasicAuth::protect_site_if_necessary() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'BuildTask', 'm' => 'run', 'n' => 'Changed type of parameter $request in BuildTask::run() from dynamic to Symfony\\Component\\Console\\Input\\InputInterface'],
        ['c' => 'CanonicalURLMiddleware', 'm' => 'getOrValidateRequest', 'n' => 'Changed type of parameter $request in CanonicalURLMiddleware::getOrValidateRequest() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'CanonicalURLMiddleware', 'm' => 'throwRedirectIfNeeded', 'n' => 'Changed type of parameter $request in CanonicalURLMiddleware::throwRedirectIfNeeded() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Debug', 'm' => 'create_debug_view', 'n' => 'Changed type of parameter $request in Debug::create_debug_view() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Debug', 'm' => 'dump', 'n' => 'Changed type of parameter $request in Debug::dump() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Debug', 'm' => 'endshow', 'n' => 'Changed type of parameter $request in Debug::endshow() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Debug', 'm' => 'message', 'n' => 'Changed type of parameter $request in Debug::message() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Debug', 'm' => 'show', 'n' => 'Changed type of parameter $request in Debug::show() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Debug', 'm' => 'supportsHTML', 'n' => 'Changed type of parameter $request in Debug::supportsHTML() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Debug', 'm' => 'text', 'n' => 'Changed type of parameter $request in Debug::text() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Director', 'm' => 'absoluteBaseURLWithAuth', 'n' => 'Changed type of parameter $request in Director::absoluteBaseURLWithAuth() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Director', 'm' => 'currentRequest', 'n' => 'Changed type of parameter $request in Director::currentRequest() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Director', 'm' => 'forceSSL', 'n' => 'Changed type of parameter $request in Director::forceSSL() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Director', 'm' => 'forceWWW', 'n' => 'Changed type of parameter $request in Director::forceWWW() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Director', 'm' => 'host', 'n' => 'Changed type of parameter $request in Director::host() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Director', 'm' => 'hostName', 'n' => 'Changed type of parameter $request in Director::hostName() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Director', 'm' => 'is_ajax', 'n' => 'Changed type of parameter $request in Director::is_ajax() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Director', 'm' => 'is_https', 'n' => 'Changed type of parameter $request in Director::is_https() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Director', 'm' => 'port', 'n' => 'Changed type of parameter $request in Director::port() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Director', 'm' => 'protocol', 'n' => 'Changed type of parameter $request in Director::protocol() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Director', 'm' => 'protocolAndHost', 'n' => 'Changed type of parameter $request in Director::protocolAndHost() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'IdentityStore', 'm' => 'logIn', 'n' => 'Changed type of parameter $request in IdentityStore::logIn() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'IdentityStore', 'm' => 'logOut', 'n' => 'Changed type of parameter $request in IdentityStore::logOut() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Member', 'm' => 'afterMemberLoggedOut', 'n' => 'Changed type of parameter $request in Member::afterMemberLoggedOut() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Member', 'm' => 'beforeMemberLoggedOut', 'n' => 'Changed type of parameter $request in Member::beforeMemberLoggedOut() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'Session', 'm' => 'destroy', 'n' => 'Changed type of parameter $request in Session::destroy() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'i18nTextCollectorTask', 'm' => 'getIsMerge', 'n' => 'Changed type of parameter $request in i18nTextCollectorTask::getIsMerge() from dynamic to Symfony\\Component\\Console\\Input\\InputInterface'],
        ['c' => 'CanonicalURLMiddleware', 'm' => 'hasBasicAuthPrompt', 'n' => 'Changed type of parameter $response in CanonicalURLMiddleware::hasBasicAuthPrompt() from HTTPResponse to HTTPResponse|null'],
        ['c' => 'PjaxResponseNegotiator', 'm' => '__construct', 'n' => 'Changed type of parameter $response in PjaxResponseNegotiator::__construct() from HTTPResponse to HTTPResponse|null'],
        ['c' => 'Authenticator', 'm' => 'authenticate', 'n' => 'Changed type of parameter $result in Authenticator::authenticate() from SilverStripe\\ORM\\ValidationResult to ValidationResult|null'],
        ['c' => 'Authenticator', 'm' => 'checkPassword', 'n' => 'Changed type of parameter $result in Authenticator::checkPassword() from SilverStripe\\ORM\\ValidationResult to ValidationResult|null'],
        ['c' => 'FormSchema', 'm' => 'getMultipartSchema', 'n' => 'Changed type of parameter $result in FormSchema::getMultipartSchema() from SilverStripe\\ORM\\ValidationResult to ValidationResult|null'],
        ['c' => 'LoginHandler', 'm' => 'checkLogin', 'n' => 'Changed type of parameter $result in LoginHandler::checkLogin() from SilverStripe\\ORM\\ValidationResult to ValidationResult|null'],
        ['c' => 'Member', 'm' => 'validateCanLogin', 'n' => 'Changed type of parameter $result in Member::validateCanLogin() from SilverStripe\\ORM\\ValidationResult to ValidationResult|null'],
        ['c' => 'MemberAuthenticator', 'm' => 'authenticateMember', 'n' => 'Changed type of parameter $result in MemberAuthenticator::authenticateMember() from SilverStripe\\ORM\\ValidationResult to ValidationResult|null'],
        ['c' => 'SSViewer', 'm' => 'setRewriteHashLinks', 'n' => 'Changed type of parameter $rewrite in SSViewer::setRewriteHashLinks() from dynamic to null|bool|string'],
        ['c' => 'SSViewer', 'm' => 'setRewriteHashLinksDefault', 'n' => 'Changed type of parameter $rewrite in SSViewer::setRewriteHashLinksDefault() from dynamic to null|bool|string'],
        ['c' => 'Cookie', 'm' => 'force_expiry', 'n' => 'Changed type of parameter $secure in Cookie::force_expiry() from dynamic to bool'],
        ['c' => 'Cookie', 'm' => 'set', 'n' => 'Changed type of parameter $secure in Cookie::set() from dynamic to bool'],
        ['c' => 'CookieJar', 'm' => 'outputCookie', 'n' => 'Changed type of parameter $secure in CookieJar::outputCookie() from dynamic to bool'],
        ['c' => 'Cookie_Backend', 'm' => 'forceExpiry', 'n' => 'Changed type of parameter $secure in Cookie_Backend::forceExpiry() from dynamic to bool'],
        ['c' => 'Cookie_Backend', 'm' => 'set', 'n' => 'Changed type of parameter $secure in Cookie_Backend::set() from dynamic to bool'],
        ['c' => 'Factory', 'm' => 'create', 'n' => 'Changed type of parameter $service in Factory::create() from dynamic to string'],
        ['c' => 'DBLocale', 'm' => 'Nice', 'n' => 'Changed type of parameter $showNative in DBLocale::Nice() from dynamic to bool'],
        ['c' => 'ConfirmedPasswordField', 'm' => 'setRequireExistingPassword', 'n' => 'Changed type of parameter $show in ConfirmedPasswordField::setRequireExistingPassword() from dynamic to bool'],
        ['c' => 'DBField', 'm' => 'create_field', 'n' => 'Changed type of parameter $spec in DBField::create_field() from dynamic to string'],
        ['c' => 'Convert', 'm' => 'linkIfMatch', 'n' => 'Changed type of parameter $string in Convert::linkIfMatch() from dynamic to string'],
        ['c' => 'DBText', 'm' => 'ContextSummary', 'n' => 'Changed type of parameter $suffix in DBText::ContextSummary() from dynamic to string|false'],
        ['c' => 'SSViewer', 'm' => 'get_templates_by_class', 'n' => 'Changed type of parameter $suffix in SSViewer::get_templates_by_class() from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'addFieldToTab', 'n' => 'Changed type of parameter $tabName in FieldList::addFieldToTab() from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'addFieldsToTab', 'n' => 'Changed type of parameter $tabName in FieldList::addFieldsToTab() from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'findOrMakeTab', 'n' => 'Changed type of parameter $tabName in FieldList::findOrMakeTab() from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'findTab', 'n' => 'Changed type of parameter $tabName in FieldList::findTab() from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'removeFieldFromTab', 'n' => 'Changed type of parameter $tabName in FieldList::removeFieldFromTab() from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'removeFieldsFromTab', 'n' => 'Changed type of parameter $tabName in FieldList::removeFieldsFromTab() from dynamic to string'],
        ['c' => 'DBField', 'm' => 'setTable', 'n' => 'Changed type of parameter $tableName in DBField::setTable() from dynamic to string'],
        ['c' => 'SSViewer', 'm' => '__construct', 'n' => 'Changed type of parameter $templates in SSViewer::__construct() from dynamic to string|array'],
        ['c' => 'HTMLEditorConfig', 'm' => 'setThemes', 'n' => 'Changed type of parameter $themes in HTMLEditorConfig::setThemes() from dynamic to array'],
        ['c' => 'SSViewer', 'm' => 'add_themes', 'n' => 'Changed type of parameter $themes in SSViewer::add_themes() from dynamic to array'],
        ['c' => 'SSViewer', 'm' => 'set_themes', 'n' => 'Changed type of parameter $themes in SSViewer::set_themes() from dynamic to array'],
        ['c' => 'DBDate', 'm' => 'getCustomFormatter', 'n' => 'Changed type of parameter $timeLength in DBDate::getCustomFormatter() from dynamic to int'],
        ['c' => 'DBDate', 'm' => 'getFormatter', 'n' => 'Changed type of parameter $timeLength in DBDate::getFormatter() from dynamic to int'],
        ['c' => 'DBTime', 'm' => 'getFormatter', 'n' => 'Changed type of parameter $timeLength in DBTime::getFormatter() from dynamic to int'],
        ['c' => 'DBDatetime', 'm' => 'withFixedNow', 'n' => 'Changed type of parameter $time in DBDatetime::withFixedNow() from dynamic to DBDatetime|string'],
        ['c' => 'TimeField', 'm' => 'internalToFrontend', 'n' => 'Changed type of parameter $time in TimeField::internalToFrontend() from dynamic to mixed'],
        ['c' => 'TimeField', 'm' => 'tidyInternal', 'n' => 'Changed type of parameter $time in TimeField::tidyInternal() from dynamic to mixed'],
        ['c' => 'DBEnum', 'm' => 'formField', 'n' => 'Changed type of parameter $title in DBEnum::formField() from dynamic to string|null'],
        ['c' => 'DBField', 'm' => 'scaffoldFormField', 'n' => 'Changed type of parameter $title in DBField::scaffoldFormField() from dynamic to string|null'],
        ['c' => 'DBField', 'm' => 'scaffoldSearchField', 'n' => 'Changed type of parameter $title in DBField::scaffoldSearchField() from dynamic to string|null'],
        ['c' => 'FieldList', 'm' => 'findOrMakeTab', 'n' => 'Changed type of parameter $title in FieldList::findOrMakeTab() from dynamic to string|null'],
        ['c' => 'CookieAuthenticationHandler', 'm' => 'setTokenCookieName', 'n' => 'Changed type of parameter $tokenCookieName in CookieAuthenticationHandler::setTokenCookieName() from dynamic to string'],
        ['c' => 'CookieAuthenticationHandler', 'm' => 'setTokenCookieSecure', 'n' => 'Changed type of parameter $tokenCookieSecure in CookieAuthenticationHandler::setTokenCookieSecure() from dynamic to bool'],
        ['c' => 'ChangePasswordHandler', 'm' => 'setSessionToken', 'n' => 'Changed type of parameter $token in ChangePasswordHandler::setSessionToken() from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'transform', 'n' => 'Changed type of parameter $trans in FieldList::transform() from dynamic to FormTransformation'],
        ['c' => 'Form', 'm' => 'sessionError', 'n' => 'Changed type of parameter $type in Form::sessionError() from dynamic to string'],
        ['c' => 'Form', 'm' => 'sessionFieldError', 'n' => 'Changed type of parameter $type in Form::sessionFieldError() from dynamic to string'],
        ['c' => 'Form', 'm' => 'sessionMessage', 'n' => 'Changed type of parameter $type in Form::sessionMessage() from dynamic to string'],
        ['c' => 'Form', 'm' => 'setValidator', 'n' => 'Changed type of parameter $validator in Form::setValidator() from SilverStripe\\Forms\\Validator to Validator|null'],
        ['c' => 'Member', 'm' => 'set_password_validator', 'n' => 'Changed type of parameter $validator in Member::set_password_validator() from SilverStripe\\Security\\PasswordValidator to PasswordValidator|null'],
        ['c' => 'Cookie', 'm' => 'set', 'n' => 'Changed type of parameter $value in Cookie::set() from dynamic to string|false'],
        ['c' => 'CookieJar', 'm' => 'outputCookie', 'n' => 'Changed type of parameter $value in CookieJar::outputCookie() from dynamic to string|false'],
        ['c' => 'Cookie_Backend', 'm' => 'set', 'n' => 'Changed type of parameter $value in Cookie_Backend::set() from dynamic to string|false'],
        ['c' => 'DBClassNameTrait', 'm' => 'setValue', 'n' => 'Changed type of parameter $value in DBClassNameTrait::setValue() from dynamic to mixed'],
        ['c' => 'DBDate', 'm' => 'parseDate', 'n' => 'Changed type of parameter $value in DBDate::parseDate() from dynamic to mixed'],
        ['c' => 'DBEnum', 'm' => 'formField', 'n' => 'Changed type of parameter $value in DBEnum::formField() from dynamic to string|null'],
        ['c' => 'DBField', 'm' => 'create_field', 'n' => 'Changed type of parameter $value in DBField::create_field() from dynamic to mixed'],
        ['c' => 'DBField', 'm' => 'prepValueForDB', 'n' => 'Changed type of parameter $value in DBField::prepValueForDB() from dynamic to mixed'],
        ['c' => 'DBField', 'm' => 'setValue', 'n' => 'Changed type of parameter $value in DBField::setValue() from dynamic to mixed'],
        ['c' => 'DBHTMLText', 'm' => 'whitelistContent', 'n' => 'Changed type of parameter $value in DBHTMLText::whitelistContent() from dynamic to mixed'],
        ['c' => 'DBPolymorphicForeignKey', 'm' => 'setClassValue', 'n' => 'Changed type of parameter $value in DBPolymorphicForeignKey::setClassValue() from dynamic to string'],
        ['c' => 'DBPolymorphicForeignKey', 'm' => 'setIDValue', 'n' => 'Changed type of parameter $value in DBPolymorphicForeignKey::setIDValue() from dynamic to int'],
        ['c' => 'DBString', 'm' => 'setNullifyEmpty', 'n' => 'Changed type of parameter $value in DBString::setNullifyEmpty() from dynamic to bool'],
        ['c' => 'DBTime', 'm' => 'parseTime', 'n' => 'Changed type of parameter $value in DBTime::parseTime() from dynamic to mixed'],
        ['c' => 'HTMLEditorConfig', 'm' => 'setOption', 'n' => 'Changed type of parameter $value in HTMLEditorConfig::setOption() from dynamic to mixed'],
        ['c' => 'NumericField', 'm' => 'cast', 'n' => 'Changed type of parameter $value in NumericField::cast() from dynamic to mixed'],
        ['c' => 'DBHTMLText', 'm' => 'setWhitelist', 'n' => 'Changed type of parameter $whitelist in DBHTMLText::setWhitelist() from dynamic to string|array'],

        ['c' => 'FieldFilterInterface', 'm' => 'apply', 'n' => 'Changed type of parameter $list in FieldFilterInterface::apply() from SilverStripe\\ORM\\Filterable to SS_List'],
        ['c' => 'CanViewPermission', 'm' => 'listPermissionCheck', 'n' => 'Changed type of parameter $obj in CanViewPermission::listPermissionCheck() from SilverStripe\\ORM\\Filterable to SS_List'],

        ['c' => 'GridFieldSiteTreeAddNewButton', 'm' => 'getAllowedChildren', 'n' => 'Changed type of parameter $parent in GridFieldSiteTreeAddNewButton::getAllowedChildren() from SiteTree to SiteTree|null'],

        ['c' => 'RealMeService', 'm' => 'getAuth', 'n' => 'Changed type of parameter $request in RealMeService::getAuth() from HTTPRequest to HTTPRequest|null'],

        ['c' => 'LeftAndMainSubsites', 'm' => 'alternateAccessCheck', 'n' => 'Changed type of parameter $member in LeftAndMainSubsites::alternateAccessCheck() from Member to Member|null'],
        ['c' => 'LeftAndMainSubsites', 'm' => 'canAccess', 'n' => 'Changed type of parameter $member in LeftAndMainSubsites::canAccess() from Member to Member|null'],

        ['c' => 'EditableFileField', 'm' => 'getFolderPermissionString', 'n' => 'Changed type of parameter $folder in EditableFileField::getFolderPermissionString() from Folder to Folder|null'],
        ['c' => 'UserDefinedFormController', 'm' => 'index', 'n' => 'Changed type of parameter $request in UserDefinedFormController::index() from HTTPRequest to HTTPRequest|null'],

        ['c' => 'Versioned', 'm' => 'get_by_stage', 'n' => 'Changed type of parameter $class in Versioned::get_by_stage() from dynamic to string'],
        ['c' => 'Versioned', 'm' => 'get_including_deleted', 'n' => 'Changed type of parameter $class in Versioned::get_including_deleted() from dynamic to string'],
        ['c' => 'Versioned', 'm' => 'get_by_stage', 'n' => 'Changed type of parameter $containerClass in Versioned::get_by_stage() from dynamic to string'],
        ['c' => 'Versioned', 'm' => 'augmentLoadLazyFields', 'n' => 'Changed type of parameter $dataQuery in Versioned::augmentLoadLazyFields() from DataQuery to DataQuery|null'],
        ['c' => 'Versioned', 'm' => 'get_by_stage', 'n' => 'Changed type of parameter $filter in Versioned::get_by_stage() from dynamic to string|array'],
        ['c' => 'Versioned', 'm' => 'get_including_deleted', 'n' => 'Changed type of parameter $filter in Versioned::get_including_deleted() from dynamic to string|array'],
        ['c' => 'Versioned', 'm' => 'get_by_stage', 'n' => 'Changed type of parameter $limit in Versioned::get_by_stage() from dynamic to string|array|null'],
        ['c' => 'Versioned', 'm' => 'get_by_stage', 'n' => 'Changed type of parameter $sort in Versioned::get_by_stage() from dynamic to string|array|null'],
        ['c' => 'Versioned', 'm' => 'get_including_deleted', 'n' => 'Changed type of parameter $sort in Versioned::get_including_deleted() from dynamic to string'],
        ['c' => 'Versioned', 'm' => 'get_by_stage', 'n' => 'Changed type of parameter $stage in Versioned::get_by_stage() from dynamic to string'],

        ['c' => 'DataObjectVersionFormFactory', 'm' => 'getFormActions', 'n' => 'Changed type of parameter $controller in DataObjectVersionFormFactory::getFormActions() from RequestHandler to RequestHandler|null'],
        ['c' => 'DataObjectVersionFormFactory', 'm' => 'getFormFields', 'n' => 'Changed type of parameter $controller in DataObjectVersionFormFactory::getFormFields() from RequestHandler to RequestHandler|null'],
        ['c' => 'HistoryViewerController', 'm' => 'compareForm', 'n' => 'Changed type of parameter $request in HistoryViewerController::compareForm() from HTTPRequest to HTTPRequest|null'],
        ['c' => 'HistoryViewerController', 'm' => 'versionForm', 'n' => 'Changed type of parameter $request in HistoryViewerController::versionForm() from HTTPRequest to HTTPRequest|null'],

        ['c' => 'CredentialRepositoryProviderTrait', 'm' => 'getCredentialRepository', 'n' => 'Changed type of parameter $registeredMethod in CredentialRepositoryProviderTrait::getCredentialRepository() from RegisteredMethod to RegisteredMethod|null'],
        ['c' => 'VerifyHandler', 'm' => 'getCredentialRequestOptions', 'n' => 'Changed type of parameter $registeredMethod in VerifyHandler::getCredentialRequestOptions() from RegisteredMethod to RegisteredMethod|null'],

        ['c' => 'WorkflowTemplate', 'm' => 'createAction', 'n' => 'Changed type of parameter $definition in WorkflowTemplate::createAction() from WorkflowDefinition to WorkflowDefinition|null'],
        ['c' => 'WorkflowInstance', 'm' => 'beginWorkflow', 'n' => 'Changed type of parameter $for in WorkflowInstance::beginWorkflow() from DataObject to DataObject|null'],
        ['c' => 'NotifyUsersWorkflowAction', 'm' => 'getMemberFields', 'n' => 'Changed type of parameter $member in NotifyUsersWorkflowAction::getMemberFields() from Member to Member|null'],

        ['c' => 'GridFieldNestedForm', 'm' => 'handleNestedItem', 'n' => 'Changed type of parameter $record in GridFieldNestedForm::handleNestedItem() from SilverStripe\\View\\ViewableData|null to ModelData|null'],
        ['c' => 'GridFieldNestedForm', 'm' => 'toggleNestedItem', 'n' => 'Changed type of parameter $record in GridFieldNestedForm::toggleNestedItem() from SilverStripe\\View\\ViewableData|null to ModelData|null'],

        ['c' => 'QueuedJobService', 'm' => 'setRunAsUser', 'n' => 'Changed type of parameter $originalUser in QueuedJobService::setRunAsUser() from Member to Member|null'],
        ['c' => 'QueuedJobService', 'm' => 'unsetRunAsUser', 'n' => 'Changed type of parameter $originalUser in QueuedJobService::unsetRunAsUser() from Member to Member|null'],
        ['c' => 'QueuedJobService', 'm' => 'unsetRunAsUser', 'n' => 'Changed type of parameter $runAsUser in QueuedJobService::unsetRunAsUser() from Member to Member|null'],

        ['c' => 'FluentExtension', 'm' => 'getDataQueryLocale', 'n' => 'Changed type of parameter $dataQuery in FluentExtension::getDataQueryLocale() from DataQuery to DataQuery|null'],
        ['c' => 'FluentFilteredExtension', 'm' => 'getDataQueryLocale', 'n' => 'Changed type of parameter $dataQuery in FluentFilteredExtension::getDataQueryLocale() from DataQuery to DataQuery|null'],
        ['c' => 'FluentIsolatedExtension', 'm' => 'getDataQueryLocale', 'n' => 'Changed type of parameter $dataQuery in FluentIsolatedExtension::getDataQueryLocale() from DataQuery to DataQuery|null'],
        ['c' => 'FluentSiteTreeExtension', 'm' => 'updateStatusFlags', 'n' => 'Changed type of parameter $flags in FluentSiteTreeExtension::updateStatusFlags() from dynamic to array'],
        ['c' => 'LocalDateTime', 'm' => 'setLocalValue', 'n' => 'Changed type of parameter $timezone in LocalDateTime::setLocalValue() from dynamic to string|null'],
        ['c' => 'LocalDateTime', 'm' => 'setLocalValue', 'n' => 'Changed type of parameter $value in LocalDateTime::setLocalValue() from dynamic to string'],

    ];

```
