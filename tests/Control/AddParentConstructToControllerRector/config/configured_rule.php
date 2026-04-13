<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Control\AddParentConstructToControllerRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        AddParentConstructToControllerRector::class,
    ]);
