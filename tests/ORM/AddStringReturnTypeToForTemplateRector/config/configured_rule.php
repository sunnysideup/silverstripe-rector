<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\ORM\AddStringReturnTypeToForTemplateRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        AddStringReturnTypeToForTemplateRector::class,
    ]);
