<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Methods\AddNewParameter;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withConfiguredRule(AddNewParameter::class, [
        // These match the 6 test cases in your fixture
        [
            'c' => 'BuildTask', 
            'm' => 'run', 
            'n' => 'Added new parameter $output in BuildTask::run()', 
            'u' => false
        ],
        [
            'c' => 'VirtualPage', 
            'm' => 'castingHelper', 
            'n' => 'Added new parameter $useFallback in VirtualPage::castingHelper()', 
            'u' => true
        ],
    ]);