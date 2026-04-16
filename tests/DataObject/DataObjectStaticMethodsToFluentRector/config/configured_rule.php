<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Netwerkstatt\SilverstripeRector\Rector\DataObject\DataObjectStaticMethodsToFluentRector;

return RectorConfig::configure()
    ->withRules([
        DataObjectStaticMethodsToFluentRector::class,
    ]);
