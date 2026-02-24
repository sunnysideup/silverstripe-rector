<?php


declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6;

use Netwerkstatt\SilverstripeRector\Interfaces\ChangeListInterface;
use Netwerkstatt\SilverstripeRector\Traits\MethodChangeHelper;

class UseInsteadChanges implements ChangeListInterface
{
    use MethodChangeHelper;
    private const LIST =
    [
        [
            'c' => 'DNADesign\\Elemental\\Models\\BaseElement',
            'm' => 'getDescription',
            'n' => 'use i18n_classDescription() instead.',
        ],

        [
            'c' => 'SilverStripe\\Admin\\LeftAndMain',
            'm' => 'currentPage',
            'n' => 'use currentRecord() instead.',
        ],
        [
            'c' => 'SilverStripe\\Admin\\LeftAndMain',
            'm' => 'currentPageID',
            'n' => 'use currentRecordID() instead.',
        ],
        [
            'c' => 'SilverStripe\\Admin\\LeftAndMain',
            'm' => 'isCurrentPage',
            'n' => 'use isCurrentRecord() instead.',
        ],
        [
            'c' => 'SilverStripe\\Admin\\LeftAndMain',
            'm' => 'setCurrentPageID',
            'n' => 'use setCurrentRecordID() instead.',
        ],

        [
            'c' => 'SilverStripe\\AssetAdmin\\Extensions\\RemoteFileModalExtension',
            'm' => 'getRequest',
            'n' => 'use $this->getOwner()->getRequest() instead.',
        ],

        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
            'm' => 'CanOrganiseSitetree',
            'n' => 'use canOrganiseTree instead.',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
            'm' => 'LinkPageAdd',
            'n' => 'use LinkRecordAdd() instead.',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
            'm' => 'LinkPageEdit',
            'n' => 'use LinkRecordEdit() instead.',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
            'm' => 'LinkPageHistory',
            'n' => 'use LinkRecordHistory() instead.',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
            'm' => 'LinkPageSettings',
            'n' => 'use LinkRecordSettings() instead.',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
            'm' => 'LinkPages',
            'n' => 'use LinkRecords instead',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
            'm' => 'LinkPagesWithSearch',
            'n' => 'use LinkRecordsWithSearch instead',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
            'm' => 'PageTypes',
            'n' => 'use RecordTypes() instead.',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
            'm' => 'SiteTreeAsUL',
            'n' => 'use TreeAsUL() instead.',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
            'm' => 'SiteTreeHints',
            'n' => 'use TreeHints() instead.',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
            'm' => 'getPageTypes',
            'n' => 'use getRecordTypes() instead.',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
            'm' => 'getSiteTreeFor',
            'n' => 'use getTreeFor() instead.',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
            'm' => 'performPublish',
            'n' => 'use RecursivePublishable::publishRecursive() instead.',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\ContentController',
            'm' => 'Menu',
            'n' => 'use getMenu() instead. You can continue to use $Menu in templates.',
        ],

        [
            'c' => 'SilverStripe\\Dev\\Deprecation',
            'm' => 'withNoReplacement',
            'n' => 'use withSuppressedNotice() instead',
        ],
        [
            'c' => 'SilverStripe\\Dev\\DevelopmentAdmin',
            'm' => 'get_links',
            'n' => 'use getLinks() instead to include permission checks',
        ],
        [
            'c' => 'SilverStripe\\Forms\\Form',
            'm' => 'validationResult',
            'n' => 'use validate() instead',
        ],
        [
            'c' => 'SilverStripe\\Forms\\FormField',
            'm' => 'extendValidationResult',
            'n' => 'use extend() directly instead',
        ],
        [
            'c' => 'SilverStripe\\Security\\InheritedPermissions',
            'm' => 'getJoinTable',
            'n' => 'use getGroupJoinTable() instead',
        ],
        [
            'c' => 'SilverStripe\\View\\SSViewer',
            'm' => 'get_base_tag',
            'n' => 'use getBaseTag() instead',
        ],

        [
            'c' => 'SilverStripe\\StaticPublishQueue\\Extension\\Engine\\SiteTreePublishingEngine',
            'm' => 'getToDelete',
            'n' => 'use getUrlsToDelete() instead',
        ],
        [
            'c' => 'SilverStripe\\StaticPublishQueue\\Extension\\Engine\\SiteTreePublishingEngine',
            'm' => 'getToUpdate',
            'n' => 'use getUrlsToUpdate() instead',
        ],
        [
            'c' => 'SilverStripe\\StaticPublishQueue\\Extension\\Engine\\SiteTreePublishingEngine',
            'm' => 'setToDelete',
            'n' => 'use setUrlsToDelete() instead',
        ],
        [
            'c' => 'SilverStripe\\StaticPublishQueue\\Extension\\Engine\\SiteTreePublishingEngine',
            'm' => 'setToUpdate',
            'n' => 'use setUrlsToUpdate() instead',
        ],

        [
            'c' => 'SilverStripe\\VendorPlugin\\Methods\\CopyMethod',
            'm' => 'copy',
            'n' => 'use Filesystem::copy instead',
        ],

        [
            'c' => 'SilverStripe\\Versioned\\Versioned',
            'm' => 'canArchive',
            'n' => 'use canDelete() instead.',
        ],

        [
            'c' => 'Symbiote\\QueuedJobs\\Tasks\\ProcessJobQueueTask',
            'm' => 'getQueue',
            'n' => 'use AbstractQueuedJob::getQueue() instead',
        ],
    ];
}
