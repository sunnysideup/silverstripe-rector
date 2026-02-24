<?php


declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6;

use Netwerkstatt\SilverstripeRector\Interfaces\ChangeListInterface;
use Netwerkstatt\SilverstripeRector\Traits\MethodChangeHelper;

class RemovedParameterChanges implements ChangeListInterface
{
    use MethodChangeHelper;
    private const LIST =
    [
        [
            'c' => 'DNADesign\\Elemental\\Controllers\\ElementalAreaController',
            'm' => 'getElementForm',
            'parameter' => '$elementID',
            'n' => 'Removed deprecated parameter $elementID in ElementalAreaController::getElementForm()',
        ],
        [
            'c' => 'DNADesign\\Elemental\\Controllers\\ElementalAreaController',
            'm' => 'elementForm',
            'parameter' => '$request',
            'n' => 'Removed deprecated parameter $request in ElementalAreaController::elementForm()',
        ],
        [
            'c' => 'SilverStripe\\Assets\\Storage\\DBFile',
            'm' => 'validate',
            'parameter' => '$filename',
            'n' => 'Removed deprecated parameter $filename in DBFile::validate()',
        ],
        [
            'c' => 'SilverStripe\\Assets\\Storage\\DBFile',
            'm' => 'validate',
            'parameter' => '$result',
            'n' => 'Removed deprecated parameter $result in DBFile::validate()',
        ],
        [
            'c' => 'SilverStripe\\View\\SSViewer',
            'm' => 'process',
            'parameter' => '$inheritedScope',
            'n' => 'Removed deprecated parameter $inheritedScope in SSViewer::process()',
        ],
        [
            'c' => 'SilverStripe\\Control\\Session',
            'm' => 'requestContainsSessionId',
            'parameter' => '$request',
            'n' => 'Removed deprecated parameter $request in Session::requestContainsSessionId()',
        ],
        [
            'c' => 'SilverStripe\\Forms\\FormField',
            'm' => 'validate',
            'parameter' => '$validator',
            'n' => 'Removed deprecated parameter $validator in FormField::validate()',
        ],
        [
            'c' => 'SilverStripe\\Versioned\\Versioned',
            'm' => 'Versions',
            'parameter' => '$having',
            'n' => 'Removed deprecated parameter $having in Versioned::Versions()',
        ],

    ];
}
