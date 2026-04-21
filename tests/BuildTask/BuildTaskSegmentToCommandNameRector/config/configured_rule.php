<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\BuildTask\BuildTaskSegmentToCommandNameRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        BuildTaskSegmentToCommandNameRector::class,
    ]);
