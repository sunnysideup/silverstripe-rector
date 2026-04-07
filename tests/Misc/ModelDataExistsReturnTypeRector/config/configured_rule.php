<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Utils\Rector\ModelDataExistsReturnTypeRector;


return RectorConfig::configure()
    ->withRules([
        ModelDataExistsReturnTypeRector::class,
    ]);