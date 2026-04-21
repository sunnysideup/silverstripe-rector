<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\v5\MigrateTaskRunToPolyExecutionRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        MigrateTaskRunToPolyExecutionRector::class,
    ]);
