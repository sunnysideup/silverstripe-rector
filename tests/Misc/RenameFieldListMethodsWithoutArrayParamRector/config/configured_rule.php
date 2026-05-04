<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Misc\RenameFieldListMethodsWithoutArrayParamRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        RenameFieldListMethodsWithoutArrayParamRector::class,
    ]);
