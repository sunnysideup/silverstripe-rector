<?php


declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6;

use Netwerkstatt\SilverstripeRector\Interfaces\ChangeListInterface;
use Netwerkstatt\SilverstripeRector\Traits\MethodChangeHelper;

class ObsoleteMethodToDoRectorChanges implements ChangeListInterface
{
    use MethodChangeHelper;
    private const LIST =
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
}
