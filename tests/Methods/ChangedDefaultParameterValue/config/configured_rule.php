<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Methods\ChangedDefaultParameterValue;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withConfiguredRule(ChangedDefaultParameterValue::class, [
        [
            'c' => 'LeftAndMain', 
            'm' => 'jsonSuccess', 
            'n' => 'Changed default value for parameter $data in LeftAndMain::jsonSuccess() from [] to null', 
            'u' => false
        ],
        [
            'c' => 'SSViewer', 
            'm' => 'process', 
            'n' => 'Changed default value for parameter $arguments in SSViewer::process() from null to []', 
            'u' => true
        ],
    ]);