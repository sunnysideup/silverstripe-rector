<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Forms\FormFieldValueToGetValueRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        FormFieldValueToGetValueRector::class,
    ]);
