<?php


declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6;

use Netwerkstatt\SilverstripeRector\Interfaces\ChangeListInterface;
use Netwerkstatt\SilverstripeRector\Traits\MethodChangeHelper;

class OtherMethodChanges implements ChangeListInterface
{
    use MethodChangeHelper;
    private const LIST =
    [
        [
            'c' => 'DNADesign\\Elemental\\Controllers\\ElementalAreaController',
            'm' => 'apiSaveForm',
            'n' => 'send a POST request to elementForm/$ItemID instead',
        ],
        [
            'c' => 'SilverStripe\\Forms\\FileUploadReceiver',
            'm' => 'Value',
            'n' => 'Removed deprecated method SilverStripe\\Forms\\FileUploadReceiver::Value()',
        ],
        [
            'c' => 'SilverStripe\\Core\\Manifest\\VersionProvider',
            'm' => 'getComposerLock',
            'n' => 'has been replaced by composer-runtime-api',
        ],
        [
            'c' => 'SilverStripe\\Forms\\SearchableDropdownTrait',
            'm' => 'validate',
            'n' => 'removed in favour of the FormField::validate() method',
        ],
        ['c' => 'SiteTree', 'm' => 'getPermissionChecker', 'n' => 'Method SiteTree::getPermissionChecker() is no longer static'],
        ['c' => 'BuildTask', 'm' => 'getDescription', 'n' => 'Method BuildTask::getDescription() is now static'],
        ['c' => 'DBEnum', 'm' => 'flushCache', 'n' => 'Method DBEnum::flushCache() is no longer static'],
        [
            'c' => 'SilverStripe\\Control\\Middleware\\URLSpecialsMiddleware\\SessionEnvTypeSwitcher',
            'm' => '',
            'n' => 'Removed deprecated trait SilverStripe\\Control\\Middleware\\URLSpecialsMiddleware\\SessionEnvTypeSwitcher - removed without equivalent functionality to replace it',
        ],
        ['c' => 'MigrationTask', 'm' => 'down', 'n' => 'Method MigrationTask::down() is now abstract'],
        ['c' => 'MigrationTask', 'm' => 'up', 'n' => 'Method MigrationTask::up() is now abstract'],
    ];
}
