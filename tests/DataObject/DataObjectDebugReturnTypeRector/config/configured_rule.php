<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\DataObject\DataObjectDebugReturnTypeRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        DataObjectDebugReturnTypeRector::class,
    ]);
