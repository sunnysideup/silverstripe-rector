<?php


declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6;

use Netwerkstatt\SilverstripeRector\Interfaces\ChangeListInterface;
use Netwerkstatt\SilverstripeRector\Traits\MethodChangeHelper;

class ReplacedWithChanges implements ChangeListInterface
{
    use MethodChangeHelper;
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
}
