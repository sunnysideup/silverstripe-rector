<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Injector\FactoryCreateMethodSignatureRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        FactoryCreateMethodSignatureRector::class,
    ]);