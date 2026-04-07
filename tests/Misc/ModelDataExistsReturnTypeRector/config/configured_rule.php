<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;


return RectorConfig::configure()
    ->withRules([
        \Netwerkstatt\SilverstripeRector\Rector\Misc\ModelDataExistsReturnTypeRector::class,
    ]);