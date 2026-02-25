<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Methods\ReturnType;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withConfiguredRule(ReturnType::class, [
        [
            'c' => 'BaseElement', 
            'm' => 'forTemplate', 
            'n' => 'Changed return type for method BaseElement::forTemplate() from dynamic to string', 
            'u' => false
        ],
        [
            'c' => 'LeftAndMain', 
            'm' => 'getRecord', 
            'n' => 'Changed return type for method LeftAndMain::getRecord() from dynamic to DataObject|null', 
            'u' => true // Enable fallback for testing unknown targets
        ],
    ]);