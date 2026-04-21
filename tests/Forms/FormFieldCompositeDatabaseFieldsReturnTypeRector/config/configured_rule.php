<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Forms\FormFieldCompositeDatabaseFieldsReturnTypeRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        FormFieldCompositeDatabaseFieldsReturnTypeRector::class,
    ]);
