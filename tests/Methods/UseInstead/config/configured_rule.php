<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Methods\UseInstead;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withConfiguredRule(UseInstead::class, [
        [
            'c' => 'SilverStripe\Admin\LeftAndMain',
            'm' => 'currentPage',
            'n' => 'use currentRecord() instead.',
            'u' => false
        ],
        [
            'c' => 'SilverStripe\CMS\Controllers\CMSMain',
            'm' => 'CanOrganiseSitetree',
            'n' => 'use canOrganiseTree instead.',
            'u' => true // fallback allowed for tests
        ],
        [
            'c' => 'SilverStripe\CMS\Controllers\ContentController',
            'm' => 'Menu',
            'n' => 'use getMenu() instead. You can continue to use $Menu in templates.', // complex note
            'u' => false
        ],
        [
            'c' => 'SilverStripe\AssetAdmin\Extensions\RemoteFileModalExtension',
            'm' => 'getRequest',
            'n' => 'use $this->getOwner()->getRequest() instead.', // complex context change note
            'u' => false
        ],
    ]);