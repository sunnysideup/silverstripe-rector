<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Misc\RenameAddFieldsToTabWithoutArrayParamRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        RenameAddFieldsToTabWithoutArrayParamRector::class,
    ]);
