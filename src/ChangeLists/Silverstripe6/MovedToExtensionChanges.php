<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6;

use Netwerkstatt\SilverstripeRector\Interfaces\ChangeListInterface;
use Netwerkstatt\SilverstripeRector\Traits\MethodChangeHelper;

class MovedToExtensionChanges implements ChangeListInterface
{
    use MethodChangeHelper;
    private const LIST =
    [
        [
            'c' => 'SilverStripe\\AssetAdmin\\Controller\\AssetAdmin',
            'm' => 'addToCampaignForm',
            'n' => 'will moved to AddToCampaignExtension',
        ],
        [
            'c' => 'SilverStripe\\AssetAdmin\\Controller\\AssetAdmin',
            'm' => 'addtocampaign',
            'n' => 'will moved to AddToCampaignExtension',
        ],
        [
            'c' => 'SilverStripe\\AssetAdmin\\Controller\\AssetAdmin',
            'm' => 'getAddToCampaignForm',
            'n' => 'will moved to AddToCampaignExtension',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSPageEditController',
            'm' => 'AddToCampaignForm',
            'n' => 'moved to AddToCampaignExtension',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSPageEditController',
            'm' => 'addtocampaign',
            'n' => 'moved to AddToCampaignExtension',
        ],
        [
            'c' => 'SilverStripe\\CMS\\Controllers\\CMSPageEditController',
            'm' => 'getAddToCampaignForm',
            'n' => 'moved to AddToCampaignExtension',
        ],
    ];
}
