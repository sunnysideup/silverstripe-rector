<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Methods\ObsoleteMethod;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withConfiguredRule(ObsoleteMethod::class, [
        [
            'c' => 'DNADesign\Elemental\Controllers\ElementalAreaController', 
            'm' => 'formAction', 
            'n' => 'removed without equivalent functionality to replace it', 
            'u' => false
        ],
        [
            'c' => 'SilverStripe\Admin\LeftAndMain', 
            'm' => 'getSearchFilter', 
            'n' => 'removed without equivalent functionality to replace it', 
            'u' => true
        ],
    ]);