<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\BuildTask\EnsureExecuteReturnsCommandSuccessRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        EnsureExecuteReturnsCommandSuccessRector::class,
    ]);
