<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Methods\RemovedParameter;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withConfiguredRule(RemovedParameter::class, [
        [
            'c' => 'DNADesign\Elemental\Controllers\ElementalAreaController',
            'm' => 'elementForm',
            'parameter' => '$request',
            'n' => 'Removed deprecated parameter $request in ElementalAreaController::elementForm()',
            'u' => false
        ],
        [
            'c' => 'SilverStripe\Forms\FormField',
            'm' => 'validate',
            'parameter' => '$validator',
            'n' => 'Removed deprecated parameter $validator in FormField::validate()',
            'u' => true // fallback allowed
        ],
    ]);