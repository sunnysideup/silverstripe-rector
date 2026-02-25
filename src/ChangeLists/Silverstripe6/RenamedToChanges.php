<?php


declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6;

use Netwerkstatt\SilverstripeRector\Interfaces\ChangeListInterface;
use Netwerkstatt\SilverstripeRector\Traits\MethodChangeHelper;

class RenamedToChanges implements ChangeListInterface
{
    use MethodChangeHelper;

    private const LIST =
    [

        [
            'c' => 'DNADesign\\Elemental\\Extensions\\ElementalPageExtension',
            'm' => 'MetaTags',
            'n' => 'renamed to updateMetaTags()',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSMain',
            'm' => 'PageList',
            'n' => 'renamed to RecordList - covered in main Rector config',
        ],
        [
            'c' => 'SilverStripe\\ORM\\CMSPreviewable',
            'm' => 'CMSEditLink',
            'n' => 'renamed to getCMSEditLink()- covered in main Rector config',
        ],
        [
            'c' => 'SilverStripe\\ORM\\Hierarchy\\Hierarchy',
            'm' => 'flushCache',
            'n' => 'renamed to onFlushCache()',
        ],
        [
            'c' => 'SilverStripe\\Security\\InheritedPermissionFlusher',
            'm' => 'flushCache',
            'n' => 'renamed to onFlushCache()',
        ],
        [
            'c' => 'SilverStripe\\MFA\\Extension\\MemberExtension',
            'm' => 'afterMemberLoggedIn',
            'n' => 'renamed to onAfterMemberLoggedIn()',
        ],
        [
            'c' => 'SilverStripe\\MFA\\Extension\\RequirementsExtension',
            'm' => 'init',
            'n' => 'renamed to onInit()',
        ],
        [
            'c' => 'SilverStripe\\SessionManager\\Extensions\\RememberLoginHashExtension',
            'm' => 'onAfterRenewToken',
            'n' => 'renamed to onAfterRenewSession()',
        ],
        [
            'c' => 'SilverStripe\\Subsites\\Extensions\\SiteTreeSubsites',
            'm' => 'MetaTags',
            'n' => 'renamed to updateMetaTags()',
        ],
        [
            'c' => 'SilverStripe\\Versioned\\Versioned',
            'm' => 'flushCache',
            'n' => 'renamed to onFlushCache()',
        ],
        [
            'c' => 'TractorCow\\Fluent\\Extension\\FluentLeftAndMainExtension',
            'm' => 'init',
            'n' => 'renamed to onInit()',
        ],
        [
            'c' => 'TractorCow\\Fluent\\Extension\\FluentSiteTreeExtension',
            'm' => 'MetaTags',
            'n' => 'renamed to updateMetaTags()',
        ],
        [
            'c' => 'TractorCow\\Fluent\\Extension\\FluentVersionedExtension',
            'm' => 'flushCache',
            'n' => 'renamed to onFlushCache()',
        ],
    ];
}
