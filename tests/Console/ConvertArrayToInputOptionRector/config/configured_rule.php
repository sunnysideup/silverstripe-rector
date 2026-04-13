<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Console\ConvertArrayToInputOptionRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        ConvertArrayToInputOptionRector::class,
    ]);
