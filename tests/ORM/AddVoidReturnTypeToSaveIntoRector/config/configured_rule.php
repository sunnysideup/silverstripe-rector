<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\ORM\AddVoidReturnTypeToSaveIntoRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        AddVoidReturnTypeToSaveIntoRector::class,
    ]);
