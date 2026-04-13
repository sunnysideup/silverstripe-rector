<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\ORM\AddUnionReturnTypeToPrepValueForDBRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        AddUnionReturnTypeToPrepValueForDBRector::class,
    ]);
