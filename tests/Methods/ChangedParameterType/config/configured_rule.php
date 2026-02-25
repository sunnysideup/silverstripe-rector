<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Methods\ChangedParameterType;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withConfiguredRule(ChangedParameterType::class, [
        [
            'c' => 'BuildTask', 
            'm' => 'run', 
            'n' => 'Changed type of parameter $request in BuildTask::run() from dynamic to Symfony\\Component\\Console\\Input\\InputInterface', 
            'u' => false
        ],
        [
            'c' => 'LeftAndMain', 
            'm' => 'jsonError', 
            'n' => 'Changed type of parameter $errorCode in LeftAndMain::jsonError() from dynamic to int', 
            'u' => true
        ],
    ]);