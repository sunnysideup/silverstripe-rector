<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Methods\Visibility;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withConfiguredRule(Visibility::class, [
        [
            'c' => 'LeftAndMain',
            'm' => 'jsonError',
            'from' => 'public',
            'to' => 'protected',
            'n' => 'Changed visibility for method LeftAndMain::jsonError() from public to protected',
            'u' => false
        ],
        [
            'c' => 'EditFormFactory',
            'm' => 'namespaceFields',
            'from' => 'protected',
            'to' => 'public',
            'n' => 'Changed visibility for method EditFormFactory::namespaceFields() from protected to public',
            'u' => true // fallback allowed for tests
        ],
    ]);