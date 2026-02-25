<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Methods\ReplacedWith;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withConfiguredRule(ReplacedWith::class, [
        [
            'c' => 'SilverStripe\UserForms\UserForm',
            'm' => 'getCMSValidator',
            'n' => 'replaced with getCMSCompositeValidator()',
            'u' => false
        ],
        [
            'c' => 'SilverStripe\View\SSViewer',
            'm' => 'fromString',
            'n' => 'replaced with SSTemplateEngine::renderString()', // non-method / complex replacement
            'u' => true // allow fallback for tests
        ],
        [
            'c' => 'SilverStripe\CMS\Controllers\CMSSiteTreeFilter',
            'm' => 'applyDefaultFilters',
            'n' => 'replaced with a SearchContext subclass', // complex replacement
            'u' => false
        ],
    ]);