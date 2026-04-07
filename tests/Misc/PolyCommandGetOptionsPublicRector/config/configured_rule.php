<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Misc\PolyCommandGetOptionsPublicRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        PolyCommandGetOptionsPublicRector::class,
    ]);