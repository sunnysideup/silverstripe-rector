<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Forms\FormFieldValidateSignatureRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        FormFieldValidateSignatureRector::class,
    ]);
