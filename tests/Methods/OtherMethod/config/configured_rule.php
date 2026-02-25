<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Methods\OtherMethod;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withConfiguredRule(OtherMethod::class, [
        [
            'c' => 'SilverStripe\Core\Manifest\VersionProvider',
            'm' => 'getComposerLock',
            'n' => 'has been replaced by composer-runtime-api',
            'u' => false
        ],
        [
            'c' => 'SiteTree', 
            'm' => 'getPermissionChecker', 
            'n' => 'Method SiteTree::getPermissionChecker() is no longer static',
            'u' => true // Set to true to test unknown receiver fallback
        ],
        [
            'c' => 'MigrationTask', 
            'm' => 'up', 
            'n' => 'Method MigrationTask::up() is now abstract',
            'u' => false
        ],
        [
            'c' => 'SilverStripe\Control\Middleware\URLSpecialsMiddleware\SessionEnvTypeSwitcher',
            'm' => '',
            'n' => 'Removed deprecated trait SilverStripe\Control\Middleware\URLSpecialsMiddleware\SessionEnvTypeSwitcher - removed without equivalent functionality to replace it',
            'u' => false
        ],
    ]);