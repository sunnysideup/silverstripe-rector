# Visibility Changes

I want a custom Rector rule called `Visibility` for the latest Rector version.

The purpose is to add a TODO upgrade comment when code calls or overrides methods whose visibility changed, and the existing code likely needs manual review.

This is especially important when visibility becomes more restrictive (for example `public` to `protected`), because external calls may no longer be valid. Visibility widening (for example `protected` to `public`) can also affect subclass overrides and compatibility.

## Source data format

For each visibility change, I have config like this:

```php
[
    'c' => 'Vendor\\Package\\SomeClass', // original class name
    'm' => 'doThing', // method name
    'from' => 'public', // previous visibility
    'to' => 'protected', // new visibility
    'n' => 'Changed visibility for method SomeClass::doThing() from public to protected', // explanation/note
    'u' => false, // true = method name is unique enough to match even when receiver type cannot be resolved
],
```

## Meaning of fields

c = class name where the method visibility changed (may be fully-qualified or short class name, depending on how it is stored in my config)

m = method name

from = old visibility (`public`, `protected`, or `private`)

to = new visibility (`public`, `protected`, or `private`)

n = human-readable upgrade note

u = whether it is safe to add the TODO when the class/type cannot be determined (because the method name is unique enough and unlikely to be a false positive)

## What the Rector rule should do

The rule must detect at least:

- instance method calls (including nullsafe calls)
- class method declarations that override changed parent methods

Implementation detail: the Rector rule may inspect whatever AST node types are appropriate to achieve this.

## Transformation required (method calls)

If code calls a changed method on an instance of the configured class (or subclass), add a TODO doc comment immediately before the call.

This is a manual review marker. The rule does not need to automatically rewrite call sites.

Before

```php
$leftAndMain->jsonError($message, 400);
```

After

```php
/** @TODO SSU RECTOR UPGRADE TASK - LeftAndMain::jsonError: Changed visibility for method LeftAndMain::jsonError() from public to protected */
$leftAndMain->jsonError($message, 400);
```

### Why method calls need review

If visibility becomes more restrictive (for example `public` → `protected`), external calls may now be invalid. Even when visibility widens, the API contract has changed and related code may require review.

## Transformation required (method overrides)

If a class method overrides a changed method from the configured class (or subclass relationship applies), add a TODO doc comment immediately before the method declaration.

Before

```php
protected function namespaceFields(array $fields)
{
    // ...
}
```

After

```php
/** @TODO SSU RECTOR UPGRADE TASK - EditFormFactory::namespaceFields: Changed visibility for method EditFormFactory::namespaceFields() from protected to public */
protected function namespaceFields(array $fields)
{
    // ...
}
```

### Why overrides need review

Visibility changes in parent methods can make subclass overrides incompatible (for example when the parent becomes more visible and the child is no longer allowed to be more restrictive). This rule should flag overrides for manual review.

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
@TODO SSU RECTOR UPGRADE TASK - LeftAndMain::jsonError: Changed visibility for method LeftAndMain::jsonError() from public to protected
```

## What I want in the answer

Please provide:

- Full Rector rule class (`Visibility`)
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
  - visibility change parent `protected` → `public` with stricter child override (should annotate override for manual review)
  - visibility change parent `public` → `protected` external call (should annotate call for manual review)

## Environment / assumptions

- I am using the latest Rector version
- I can install additional composer packages if needed

## Actual Data

Below is the data we have. I will add the `u` value later. This gives you a good idea of what to expect.

NOW for: `Visibility`

With the following changes:

```php

    private const LIST =
    [

        [
            'c' => 'EditFormFactory',
            'm' => 'namespaceFields',
            'from' => 'protected',
            'to' => 'public',
            'n' => 'Changed visibility for method EditFormFactory::namespaceFields() from protected to public',
        ],
        [
            'c' => 'ElementalAreaUsedOnTableExtension',
            'm' => 'updateUsageAncestorDataObjects',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method ElementalAreaUsedOnTableExtension::updateUsageAncestorDataObjects() from public to protected',
        ],
        [
            'c' => 'ElementalAreaUsedOnTableExtension',
            'm' => 'updateUsageDataObject',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method ElementalAreaUsedOnTableExtension::updateUsageDataObject() from public to protected',
        ],
        [
            'c' => 'ElementalAreaUsedOnTableExtension',
            'm' => 'updateUsageExcludedClasses',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method ElementalAreaUsedOnTableExtension::updateUsageExcludedClasses() from public to protected',
        ],
        [
            'c' => 'ElementalPageExtension',
            'm' => 'updateAnchorsOnPage',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method ElementalPageExtension::updateAnchorsOnPage() from public to protected',
        ],
        [
            'c' => 'GridFieldAddNewMultiClassHandlerExtension',
            'm' => 'updateItemEditForm',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method GridFieldAddNewMultiClassHandlerExtension::updateItemEditForm() from public to protected',
        ],
        [
            'c' => 'GridFieldDetailFormItemRequestExtension',
            'm' => 'updateBreadcrumbs',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method GridFieldDetailFormItemRequestExtension::updateBreadcrumbs() from public to protected',
        ],
        [
            'c' => 'AdminErrorExtension',
            'm' => 'onBeforeHTTPError',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method AdminErrorExtension::onBeforeHTTPError() from public to protected',
        ],
        [
            'c' => 'GridFieldDetailFormPreviewExtension',
            'm' => 'updateItemEditForm',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method GridFieldDetailFormPreviewExtension::updateItemEditForm() from public to protected',
        ],
        [
            'c' => 'GridFieldPrintButtonExtension',
            'm' => 'updatePrintData',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method GridFieldPrintButtonExtension::updatePrintData() from public to protected',
        ],
        [
            'c' => 'LeftAndMain',
            'm' => 'getSchemaResponse',
            'from' => 'protected',
            'to' => 'public',
            'n' => 'Changed visibility for method LeftAndMain::getSchemaResponse() from protected to public',
        ],
        [
            'c' => 'LeftAndMain',
            'm' => 'jsonError',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method LeftAndMain::jsonError() from public to protected',
        ],
        [
            'c' => 'AssetAdminFile',
            'm' => 'updateCMSEditLink',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method AssetAdminFile::updateCMSEditLink() from public to protected',
        ],
        [
            'c' => 'UsedOnTableExtension',
            'm' => 'updateUsageDataObject',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method UsedOnTableExtension::updateUsageDataObject() from public to protected',
        ],
        [
            'c' => 'UsedOnTableExtension',
            'm' => 'updateUsageExcludedClasses',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method UsedOnTableExtension::updateUsageExcludedClasses() from public to protected',
        ],
        [
            'c' => 'File',
            'm' => 'onAfterRevertToLive',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method File::onAfterRevertToLive() from public to protected',
        ],
        [
            'c' => 'File',
            'm' => 'onAfterUpload',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method File::onAfterUpload() from public to protected',
        ],
        [
            'c' => 'FileLinkTracking',
            'm' => 'augmentSyncLinkTracking',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FileLinkTracking::augmentSyncLinkTracking() from public to protected',
        ],
        [
            'c' => 'Folder',
            'm' => 'onAfterSkippedWrite',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method Folder::onAfterSkippedWrite() from public to protected',
        ],
        [
            'c' => 'BlogPost',
            'm' => 'onAfterWrite',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method BlogPost::onAfterWrite() from public to protected',
        ],
        [
            'c' => 'BlogPost',
            'm' => 'onBeforePublish',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method BlogPost::onBeforePublish() from public to protected',
        ],
        [
            'c' => 'BlogPostFilter',
            'm' => 'augmentLoadLazyFields',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method BlogPostFilter::augmentLoadLazyFields() from public to protected',
        ],
        [
            'c' => 'CMSMain',
            'm' => 'getCMSTreeTitle',
            'from' => 'protected',
            'to' => 'public',
            'n' => 'Changed visibility for method CMSMain::getCMSTreeTitle() from protected to public',
        ],
        [
            'c' => 'LeftAndMainBatchActionsExtension',
            'm' => 'updateBatchActionsForm',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method LeftAndMainBatchActionsExtension::updateBatchActionsForm() from public to protected',
        ],
        [
            'c' => 'SiteTree',
            'm' => 'onAfterPublish',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method SiteTree::onAfterPublish() from public to protected',
        ],
        [
            'c' => 'SiteTree',
            'm' => 'onAfterRevertToLive',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method SiteTree::onAfterRevertToLive() from public to protected',
        ],
        [
            'c' => 'SiteTree',
            'm' => 'onBeforeDuplicate',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method SiteTree::onBeforeDuplicate() from public to protected',
        ],
        [
            'c' => 'SiteTreeLinkTracking',
            'm' => 'augmentSyncLinkTracking',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method SiteTreeLinkTracking::augmentSyncLinkTracking() from public to protected',
        ],
        [
            'c' => 'VirtualPage',
            'm' => 'onBeforeWrite',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method VirtualPage::onBeforeWrite() from public to protected',
        ],
        [
            'c' => 'ErrorPageControllerExtension',
            'm' => 'onBeforeHTTPError',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method ErrorPageControllerExtension::onBeforeHTTPError() from public to protected',
        ],
        [
            'c' => 'ErrorPageFileExtension',
            'm' => 'getErrorRecordFor',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method ErrorPageFileExtension::getErrorRecordFor() from public to protected',
        ],
        [
            'c' => 'DateField',
            'm' => 'internalToFrontend',
            'from' => 'protected',
            'to' => 'public',
            'n' => 'Changed visibility for method DateField::internalToFrontend() from protected to public',
        ],
        [
            'c' => 'DevelopmentAdmin',
            'm' => 'getLinks',
            'from' => 'protected',
            'to' => 'public',
            'n' => 'Changed visibility for method DevelopmentAdmin::getLinks() from protected to public',
        ],
        [
            'c' => 'TaskRunner',
            'm' => 'getTaskList',
            'from' => 'protected',
            'to' => 'public',
            'n' => 'Changed visibility for method TaskRunner::getTaskList() from protected to public',
        ],
        [
            'c' => 'TimeField',
            'm' => 'internalToFrontend',
            'from' => 'protected',
            'to' => 'public',
            'n' => 'Changed visibility for method TimeField::internalToFrontend() from protected to public',
        ],
        [
            'c' => 'ClientConfigProvider',
            'm' => 'updateClientConfig',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method ClientConfigProvider::updateClientConfig() from public to protected',
        ],
        [
            'c' => 'QueryRecorderExtension',
            'm' => 'augmentDataQueryCreation',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method QueryRecorderExtension::augmentDataQueryCreation() from public to protected',
        ],
        [
            'c' => 'TestSessionEnvironmentExtension',
            'm' => 'onAfterStartTestSession',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method TestSessionEnvironmentExtension::onAfterStartTestSession() from public to protected',
        ],
        [
            'c' => 'FluentLinkExtension',
            'm' => 'updateCMSFields',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentLinkExtension::updateCMSFields() from public to protected',
        ],
        [
            'c' => 'UsedOnTableExtension',
            'm' => 'updateUsageAncestorDataObjects',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method UsedOnTableExtension::updateUsageAncestorDataObjects() from public to protected',
        ],
        [
            'c' => 'EnablerExtension',
            'm' => 'afterCallActionHandler',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method EnablerExtension::afterCallActionHandler() from public to protected',
        ],
        [
            'c' => 'EnablerExtension',
            'm' => 'beforeCallActionHandler',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method EnablerExtension::beforeCallActionHandler() from public to protected',
        ],
        [
            'c' => 'MFAResetExtension',
            'm' => 'handleAccountReset',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method MFAResetExtension::handleAccountReset() from public to protected',
        ],
        [
            'c' => 'SiteTreeExtension',
            'm' => 'canView',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method SiteTreeExtension::canView() from public to protected',
        ],
        [
            'c' => 'QueuedJobDescriptorExtension',
            'm' => 'onAfterBuild',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method QueuedJobDescriptorExtension::onAfterBuild() from public to protected',
        ],
        [
            'c' => 'RememberLoginHashExtension',
            'm' => 'onAfterGenerateToken',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method RememberLoginHashExtension::onAfterGenerateToken() from public to protected',
        ],
        [
            'c' => 'ShareDraftContentFileShortcodeProviderExtension',
            'm' => 'updateGrant',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method ShareDraftContentFileShortcodeProviderExtension::updateGrant() from public to protected',
        ],
        [
            'c' => 'SiteTreePublishingEngine',
            'm' => 'onAfterPublishRecursive',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method SiteTreePublishingEngine::onAfterPublishRecursive() from public to protected',
        ],
        [
            'c' => 'SiteTreePublishingEngine',
            'm' => 'onBeforePublishRecursive',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method SiteTreePublishingEngine::onBeforePublishRecursive() from public to protected',
        ],
        [
            'c' => 'BaseElementSubsites',
            'm' => 'updatePreviewLink',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method BaseElementSubsites::updatePreviewLink() from public to protected',
        ],
        [
            'c' => 'CMSPageAddControllerExtension',
            'm' => 'updatePageOptions',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method CMSPageAddControllerExtension::updatePageOptions() from public to protected',
        ],
        [
            'c' => 'ErrorPageSubsite',
            'm' => 'updateErrorFilename',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method ErrorPageSubsite::updateErrorFilename() from public to protected',
        ],
        [
            'c' => 'FileSubsites',
            'm' => 'cacheKeyComponent',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FileSubsites::cacheKeyComponent() from public to protected',
        ],
        [
            'c' => 'FileSubsites',
            'm' => 'onAfterUpload',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FileSubsites::onAfterUpload() from public to protected',
        ],
        [
            'c' => 'FolderFormFactoryExtension',
            'm' => 'updateFormFields',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FolderFormFactoryExtension::updateFormFields() from public to protected',
        ],
        [
            'c' => 'GroupSubsites',
            'm' => 'updateTreeTitle',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method GroupSubsites::updateTreeTitle() from public to protected',
        ],
        [
            'c' => 'HintsCacheKeyExtension',
            'm' => 'updateHintsCacheKey',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method HintsCacheKeyExtension::updateHintsCacheKey() from public to protected',
        ],
        [
            'c' => 'LeftAndMainSubsites',
            'm' => 'canAccess',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method LeftAndMainSubsites::canAccess() from public to protected',
        ],
        [
            'c' => 'LeftAndMainSubsites',
            'm' => 'onAfterSave',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method LeftAndMainSubsites::onAfterSave() from public to protected',
        ],
        [
            'c' => 'LeftAndMainSubsites',
            'm' => 'onBeforeInit',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method LeftAndMainSubsites::onBeforeInit() from public to protected',
        ],
        [
            'c' => 'LeftAndMainSubsites',
            'm' => 'updatePageOptions',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method LeftAndMainSubsites::updatePageOptions() from public to protected',
        ],
        [
            'c' => 'SiteConfigSubsites',
            'm' => 'cacheKeyComponent',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method SiteConfigSubsites::cacheKeyComponent() from public to protected',
        ],
        [
            'c' => 'SiteTreeSubsites',
            'm' => 'alternateSiteConfig',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method SiteTreeSubsites::alternateSiteConfig() from public to protected',
        ],
        [
            'c' => 'SiteTreeSubsites',
            'm' => 'augmentSyncLinkTracking',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method SiteTreeSubsites::augmentSyncLinkTracking() from public to protected',
        ],
        [
            'c' => 'SiteTreeSubsites',
            'm' => 'augmentValidURLSegment',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method SiteTreeSubsites::augmentValidURLSegment() from public to protected',
        ],
        [
            'c' => 'SiteTreeSubsites',
            'm' => 'cacheKeyComponent',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method SiteTreeSubsites::cacheKeyComponent() from public to protected',
        ],
        [
            'c' => 'SiteTreeSubsites',
            'm' => 'canAddChildren',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method SiteTreeSubsites::canAddChildren() from public to protected',
        ],
        [
            'c' => 'SiteTreeSubsites',
            'm' => 'canPublish',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method SiteTreeSubsites::canPublish() from public to protected',
        ],
        [
            'c' => 'SiteTreeSubsites',
            'm' => 'onBeforeDuplicate',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method SiteTreeSubsites::onBeforeDuplicate() from public to protected',
        ],
        [
            'c' => 'SiteTreeSubsites',
            'm' => 'updatePreviewLink',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method SiteTreeSubsites::updatePreviewLink() from public to protected',
        ],
        [
            'c' => 'UsedOnTableExtension',
            'm' => 'updateUsageAncestorDataObjects',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method UsedOnTableExtension::updateUsageAncestorDataObjects() from public to protected',
        ],
        [
            'c' => 'UsedOnTableExtension',
            'm' => 'updateUsageDataObject',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method UsedOnTableExtension::updateUsageDataObject() from public to protected',
        ],
        [
            'c' => 'UserFormFieldEditorExtension',
            'm' => 'onAfterDuplicate',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method UserFormFieldEditorExtension::onAfterDuplicate() from public to protected',
        ],
        [
            'c' => 'UserFormFieldEditorExtension',
            'm' => 'onAfterPublish',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method UserFormFieldEditorExtension::onAfterPublish() from public to protected',
        ],
        [
            'c' => 'UserFormFieldEditorExtension',
            'm' => 'onAfterRevertToLive',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method UserFormFieldEditorExtension::onAfterRevertToLive() from public to protected',
        ],
        [
            'c' => 'UserFormFieldEditorExtension',
            'm' => 'onAfterUnpublish',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method UserFormFieldEditorExtension::onAfterUnpublish() from public to protected',
        ],
        [
            'c' => 'UserFormFileExtension',
            'm' => 'updateTrackedFormUpload',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method UserFormFileExtension::updateTrackedFormUpload() from public to protected',
        ],
        [
            'c' => 'RecursivePublishable',
            'm' => 'onBeforeDuplicate',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method RecursivePublishable::onBeforeDuplicate() from public to protected',
        ],
        [
            'c' => 'RecursivePublishableHandler',
            'm' => 'onAfterSave',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method RecursivePublishableHandler::onAfterSave() from public to protected',
        ],
        [
            'c' => 'Versioned',
            'm' => 'augmentDataQueryCreation',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method Versioned::augmentDataQueryCreation() from public to protected',
        ],
        [
            'c' => 'Versioned',
            'm' => 'augmentLoadLazyFields',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method Versioned::augmentLoadLazyFields() from public to protected',
        ],
        [
            'c' => 'Versioned',
            'm' => 'cacheKeyComponent',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method Versioned::cacheKeyComponent() from public to protected',
        ],
        [
            'c' => 'Versioned',
            'm' => 'canView',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method Versioned::canView() from public to protected',
        ],
        [
            'c' => 'Versioned',
            'm' => 'onAfterSkippedWrite',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method Versioned::onAfterSkippedWrite() from public to protected',
        ],
        [
            'c' => 'Versioned',
            'm' => 'onBeforeDuplicate',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method Versioned::onBeforeDuplicate() from public to protected',
        ],
        [
            'c' => 'Versioned',
            'm' => 'onPrepopulateTreeDataCache',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method Versioned::onPrepopulateTreeDataCache() from public to protected',
        ],
        [
            'c' => 'Versioned',
            'm' => 'updateInheritableQueryParams',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method Versioned::updateInheritableQueryParams() from public to protected',
        ],
        [
            'c' => 'VersionedGridFieldArchiveExtension',
            'm' => 'updateConfig',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method VersionedGridFieldArchiveExtension::updateConfig() from public to protected',
        ],
        [
            'c' => 'VersionedGridFieldDetailForm',
            'm' => 'updateItemRequestClass',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method VersionedGridFieldDetailForm::updateItemRequestClass() from public to protected',
        ],
        [
            'c' => 'VersionedStateExtension',
            'm' => 'updateLink',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method VersionedStateExtension::updateLink() from public to protected',
        ],
        [
            'c' => 'VersionedTableDataQueryExtension',
            'm' => 'updateJoinTableName',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method VersionedTableDataQueryExtension::updateJoinTableName() from public to protected',
        ],
        [
            'c' => 'VersionedTestSessionExtension',
            'm' => 'updateGetURL',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method VersionedTestSessionExtension::updateGetURL() from public to protected',
        ],
        [
            'c' => 'VersionedTestSessionExtension',
            'm' => 'updatePostURL',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method VersionedTestSessionExtension::updatePostURL() from public to protected',
        ],
        [
            'c' => 'ArchiveRestoreAction',
            'm' => 'updateItemEditForm',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method ArchiveRestoreAction::updateItemEditForm() from public to protected',
        ],
        [
            'c' => 'UsedOnTableExtension',
            'm' => 'updateUsageExcludedClasses',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method UsedOnTableExtension::updateUsageExcludedClasses() from public to protected',
        ],
        [
            'c' => 'AdvancedWorkflowExtension',
            'm' => 'updateEditForm',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method AdvancedWorkflowExtension::updateEditForm() from public to protected',
        ],
        [
            'c' => 'AdvancedWorkflowExtension',
            'm' => 'updateItemEditForm',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method AdvancedWorkflowExtension::updateItemEditForm() from public to protected',
        ],
        [
            'c' => 'WorkflowApplicable',
            'm' => 'updateSettingsFields',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method WorkflowApplicable::updateSettingsFields() from public to protected',
        ],
        [
            'c' => 'WorkflowEmbargoExpiryExtension',
            'm' => 'onBeforeDuplicate',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method WorkflowEmbargoExpiryExtension::onBeforeDuplicate() from public to protected',
        ],
        [
            'c' => 'WorkflowEmbargoExpiryExtension',
            'm' => 'updateStatusFlags',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method WorkflowEmbargoExpiryExtension::updateStatusFlags() from public to protected',
        ],
        [
            'c' => 'GridFieldDetailFormItemRequestExtension',
            'm' => 'updateFormActions',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method GridFieldDetailFormItemRequestExtension::updateFormActions() from public to protected',
        ],
        [
            'c' => 'FluentChangesExtension',
            'm' => 'updateChangeType',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentChangesExtension::updateChangeType() from public to protected',
        ],
        [
            'c' => 'FluentDirectorExtension',
            'm' => 'updateRules',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentDirectorExtension::updateRules() from public to protected',
        ],
        [
            'c' => 'FluentExtension',
            'm' => 'cacheKeyComponent',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentExtension::cacheKeyComponent() from public to protected',
        ],
        [
            'c' => 'FluentExtension',
            'm' => 'updateLocalisationTabColumns',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentExtension::updateLocalisationTabColumns() from public to protected',
        ],
        [
            'c' => 'FluentExtension',
            'm' => 'updateLocalisationTabConfig',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentExtension::updateLocalisationTabConfig() from public to protected',
        ],
        [
            'c' => 'FluentFilteredExtension',
            'm' => 'updateLocalisationTabColumns',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentFilteredExtension::updateLocalisationTabColumns() from public to protected',
        ],
        [
            'c' => 'FluentFilteredExtension',
            'm' => 'updateLocalisationTabConfig',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentFilteredExtension::updateLocalisationTabConfig() from public to protected',
        ],
        [
            'c' => 'FluentFilteredExtension',
            'm' => 'updateStatusFlags',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentFilteredExtension::updateStatusFlags() from public to protected',
        ],
        [
            'c' => 'FluentGridFieldExtension',
            'm' => 'updateFormActions',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentGridFieldExtension::updateFormActions() from public to protected',
        ],
        [
            'c' => 'FluentIsolatedExtension',
            'm' => 'augmentDataQueryCreation',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentIsolatedExtension::augmentDataQueryCreation() from public to protected',
        ],
        [
            'c' => 'FluentMemberExtension',
            'm' => 'updateGroups',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentMemberExtension::updateGroups() from public to protected',
        ],
        [
            'c' => 'FluentObjectTrait',
            'm' => 'augmentDataQueryCreation',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentObjectTrait::augmentDataQueryCreation() from public to protected',
        ],
        [
            'c' => 'FluentObjectTrait',
            'm' => 'updateLocalisationTabColumns',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentObjectTrait::updateLocalisationTabColumns() from public to protected',
        ],
        [
            'c' => 'FluentObjectTrait',
            'm' => 'updateLocalisationTabConfig',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentObjectTrait::updateLocalisationTabConfig() from public to protected',
        ],
        [
            'c' => 'FluentReadVersionsExtension',
            'm' => 'updateList',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentReadVersionsExtension::updateList() from public to protected',
        ],
        [
            'c' => 'FluentSiteTreeExtension',
            'm' => 'updateLink',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentSiteTreeExtension::updateLink() from public to protected',
        ],
        [
            'c' => 'FluentSiteTreeExtension',
            'm' => 'updateRelativeLink',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentSiteTreeExtension::updateRelativeLink() from public to protected',
        ],
        [
            'c' => 'FluentSiteTreeExtension',
            'm' => 'updateStatusFlags',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentSiteTreeExtension::updateStatusFlags() from public to protected',
        ],
        [
            'c' => 'FluentVersionedExtension',
            'm' => 'onPrepopulateTreeDataCache',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentVersionedExtension::onPrepopulateTreeDataCache() from public to protected',
        ],
        [
            'c' => 'FluentVersionedExtension',
            'm' => 'updateGetVersionNumberByStage',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentVersionedExtension::updateGetVersionNumberByStage() from public to protected',
        ],
        [
            'c' => 'FluentVersionedExtension',
            'm' => 'updateIsArchived',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentVersionedExtension::updateIsArchived() from public to protected',
        ],
        [
            'c' => 'FluentVersionedExtension',
            'm' => 'updatePrePopulateVersionNumberCache',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentVersionedExtension::updatePrePopulateVersionNumberCache() from public to protected',
        ],
        [
            'c' => 'FluentVersionedExtension',
            'm' => 'updateStagesDiffer',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method FluentVersionedExtension::updateStagesDiffer() from public to protected',
        ],
        [
            'c' => 'UsesDeletePolicy',
            'm' => 'updateDeleteTables',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method UsesDeletePolicy::updateDeleteTables() from public to protected',
        ],

    ];


```
