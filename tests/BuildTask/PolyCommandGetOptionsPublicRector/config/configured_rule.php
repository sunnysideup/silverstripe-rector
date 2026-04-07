<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\BuildTask\PolyCommandGetOptionsPublicRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        PolyCommandGetOptionsPublicRector::class,
    ]);