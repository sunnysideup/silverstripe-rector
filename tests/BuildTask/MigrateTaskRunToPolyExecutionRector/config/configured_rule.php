<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\BuildTask\MigrateTaskRunToPolyExecutionRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        MigrateTaskRunToPolyExecutionRector::class,
    ]);
