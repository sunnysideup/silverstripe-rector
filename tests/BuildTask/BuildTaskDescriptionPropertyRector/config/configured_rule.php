<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\BuildTask\BuildTaskDescriptionPropertyRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        BuildTaskDescriptionPropertyRector::class,
    ]);
