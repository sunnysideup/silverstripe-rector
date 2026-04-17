<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\ORM\RemoveEmptyFilterRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        RemoveEmptyFilterRector::class,
    ]);
