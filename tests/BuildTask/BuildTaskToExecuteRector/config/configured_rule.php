<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\BuildTask\BuildTaskToExecuteRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        BuildTaskToExecuteRector::class,
    ]);
