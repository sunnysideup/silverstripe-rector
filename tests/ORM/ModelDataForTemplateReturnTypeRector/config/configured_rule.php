<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\ORM\ModelDataForTemplateReturnTypeRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        ModelDataForTemplateReturnTypeRector::class,
    ]);
