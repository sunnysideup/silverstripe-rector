<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Methods\RenamedParameter;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withConfiguredRule(RenamedParameter::class, [
        [
            'c' => 'BuildTask', 
            'm' => 'run', 
            'n' => 'Renamed parameter $request in BuildTask::run() to $input', 
            'u' => false
        ],
        [
            'c' => 'VirtualPage', 
            'm' => 'hasField', 
            'n' => 'Renamed parameter $field in VirtualPage::hasField() to $fieldName', 
            'u' => true // fallback allowed for tests
        ],
    ]);