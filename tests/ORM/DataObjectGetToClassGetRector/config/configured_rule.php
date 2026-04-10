<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\ORM\DataObjectGetToClassGetRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        DataObjectGetToClassGetRector::class,
    ]);
