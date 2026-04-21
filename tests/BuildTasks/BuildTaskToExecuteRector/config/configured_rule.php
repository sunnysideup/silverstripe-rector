<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\BuildTasks\BuildTaskToExecuteRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        BuildTaskToExecuteRector::class,
    ]);
