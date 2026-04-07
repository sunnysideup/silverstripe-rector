<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Netwerkstatt\SilverstripeRector\Rector\Misc\ModelDataExistsReturnTypeRector;

return RectorConfig::configure()
    ->withRules([
        ModelDataExistsReturnTypeRector::class,
    ]);