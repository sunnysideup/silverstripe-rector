<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\BuildTask\BuildTaskIsEnabledPropertyRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        BuildTaskIsEnabledPropertyRector::class,
    ]);
