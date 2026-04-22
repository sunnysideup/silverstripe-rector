<?php


declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6;

use Netwerkstatt\SilverstripeRector\Interfaces\ChangeListInterface;
use Netwerkstatt\SilverstripeRector\Traits\MethodChangeHelper;

class RenamedParameterChanges implements ChangeListInterface
{
    use MethodChangeHelper;

    private const LIST = [

        [
            'c' => 'BuildTask',
            'm' => 'run',
            'n' => 'Renamed parameter $request in BuildTask::run() to $input',
        ],
        [
            'c' => 'DBField',
            'm' => 'saveInto',
            'n' => 'Renamed parameter $dataObject in DBField::saveInto() to $model',
        ],
        [
            'c' => 'DataObject',
            'm' => 'get',
            'n' => 'Renamed parameter $join in DataObject::get() to $limit',
        ],
        [
            'c' => 'DataObjectInterface',
            'm' => '__get',
            'n' => 'Renamed parameter $fieldName in DataObjectInterface::__get() to $property',
        ],
        [
            'c' => 'DateField',
            'm' => 'internalToFrontend',
            'n' => 'Renamed parameter $date in DateField::internalToFrontend() to $value',
        ],
        [
            'c' => 'DatetimeField',
            'm' => 'internalToFrontend',
            'n' => 'Renamed parameter $datetime in DatetimeField::internalToFrontend() to $value',
        ],
        [
            'c' => 'HistoryViewerController',
            'm' => 'getRecordVersion',
            'n' => 'Renamed parameter $recordClass in HistoryViewerController::getRecordVersion() to $dataClass',
        ],
        [
            'c' => 'Image_Backend',
            'm' => 'paddedResize',
            'n' => 'Renamed parameter $backgroundColor in Image_Backend::paddedResize() to $backgroundColour',
        ],
        [
            'c' => 'MemcachedCacheFactory',
            'm' => '__construct',
            'n' => 'Renamed parameter $memcachedClient in MemcachedCacheFactory::__construct() to $logger',
        ],
        [
            'c' => 'SSViewer',
            'm' => '__construct',
            'n' => 'Renamed parameter $parser in SSViewer::__construct() to $templateEngine',
        ],
        [
            'c' => 'SSViewer',
            'm' => 'process',
            'n' => 'Renamed parameter $arguments in SSViewer::process() to $overlay',
        ],
        [
            'c' => 'Subsite',
            'm' => 'get_from_all_subsites',
            'n' => 'Renamed parameter $join in Subsite::get_from_all_subsites() to $limit',
        ],
        [
            'c' => 'TimeField',
            'm' => 'internalToFrontend',
            'n' => 'Renamed parameter $time in TimeField::internalToFrontend() to $value',
        ],
        [
            'c' => 'Versioned',
            'm' => 'get_by_stage',
            'n' => 'Renamed parameter $join in Versioned::get_by_stage() to $limit',
        ],
        [
            'c' => 'VirtualPage',
            'm' => 'hasField',
            'n' => 'Renamed parameter $field in VirtualPage::hasField() to $fieldName',
        ],

    ];
}
