<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\ORM\DBVarcharURLReturnTypeRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        DBVarcharURLReturnTypeRector::class,
    ]);
