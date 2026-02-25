<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Methods\RenamedTo;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withConfiguredRule(RenamedTo::class, [
        [
            'c' => 'TractorCow\Fluent\Extension\FluentSiteTreeExtension',
            'm' => 'MetaTags',
            'n' => 'renamed to updateMetaTags()',
            'u' => false
        ],
        [
            'c' => 'SilverStripe\MFA\Extension\RequirementsExtension',
            'm' => 'init',
            'n' => 'renamed to onInit()',
            'u' => true // fallback allowed for tests
        ],
        [
            'c' => 'SilverStripe\ORM\CMSPreviewable',
            'm' => 'CMSEditLink',
            'n' => 'changed to something entirely different', // ambiguous, should not auto-rename
            'u' => false
        ],
    ]);