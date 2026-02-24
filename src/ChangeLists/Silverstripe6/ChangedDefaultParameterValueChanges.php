<?php


declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6;

use Netwerkstatt\SilverstripeRector\Interfaces\ChangeListInterface;
use Netwerkstatt\SilverstripeRector\Traits\MethodChangeHelper;

class ChangedDefaultParameterValueChanges implements ChangeListInterface
{
    use MethodChangeHelper;
    private const LIST = [
        ['c' => 'LeftAndMain', 'm' => 'jsonSuccess', 'n' => 'Changed default value for parameter $data in LeftAndMain::jsonSuccess() from [] to null'],
        ['c' => 'LeftAndMain', 'm' => 'jsonError', 'n' => 'Changed default value for parameter $errorMessage in LeftAndMain::jsonError() from null to \'\''],

        ['c' => 'AssetFormFactory', 'm' => 'getFormActions', 'n' => 'Changed default value for parameter $controller in AssetFormFactory::getFormActions() from null to none'],
        ['c' => 'AssetFormFactory', 'm' => 'getFormFields', 'n' => 'Changed default value for parameter $controller in AssetFormFactory::getFormFields() from null to none'],
        ['c' => 'AssetFormFactory', 'm' => 'getValidator', 'n' => 'Changed default value for parameter $controller in AssetFormFactory::getValidator() from null to none'],
        ['c' => 'FileSearchFormFactory', 'm' => 'getFormFields', 'n' => 'Changed default value for parameter $controller in FileSearchFormFactory::getFormFields() from null to none'],

        ['c' => 'BlogPostFilter', 'm' => 'augmentLoadLazyFields', 'n' => 'Changed default value for parameter $dataQuery in BlogPostFilter::augmentLoadLazyFields() from null to none'],

        ['c' => 'SSViewer', 'm' => 'process', 'n' => 'Changed default value for parameter $arguments in SSViewer::process() from null to []'],
        ['c' => 'CookieJar', 'm' => 'outputCookie', 'n' => 'Changed default value for parameter $expiry in CookieJar::outputCookie() from 90 to none'],
        ['c' => 'Form', 'm' => 'loadDataFrom', 'n' => 'Changed default value for parameter $fieldList in Form::loadDataFrom() from null to []'],
        ['c' => 'DataObject', 'm' => 'get', 'n' => 'Changed default value for parameter $join in DataObject::get() from \'\' to null'],
        ['c' => 'DB', 'm' => 'connect', 'n' => 'Changed default value for parameter $label in DB::connect() from \'default\' to DB::CONN_DYNAMIC'],
        ['c' => 'SearchContext', 'm' => 'getQuery', 'n' => 'Changed default value for parameter $limit in SearchContext::getQuery() from false to null'],
        ['c' => 'DB', 'm' => 'build_sql', 'n' => 'Changed default value for parameter $name in DB::build_sql() from \'default\' to DB::CONN_DYNAMIC'],
        ['c' => 'DB', 'm' => 'getConfig', 'n' => 'Changed default value for parameter $name in DB::getConfig() from \'default\' to DB::CONN_PRIMARY'],
        ['c' => 'DB', 'm' => 'get_conn', 'n' => 'Changed default value for parameter $name in DB::get_conn() from \'default\' to DB::CONN_DYNAMIC'],
        ['c' => 'DB', 'm' => 'get_connector', 'n' => 'Changed default value for parameter $name in DB::get_connector() from \'default\' to DB::CONN_DYNAMIC'],
        ['c' => 'DB', 'm' => 'get_schema', 'n' => 'Changed default value for parameter $name in DB::get_schema() from \'default\' to DB::CONN_DYNAMIC'],
        ['c' => 'DB', 'm' => 'setConfig', 'n' => 'Changed default value for parameter $name in DB::setConfig() from \'default\' to DB::CONN_PRIMARY'],
        ['c' => 'DB', 'm' => 'set_conn', 'n' => 'Changed default value for parameter $name in DB::set_conn() from \'default\' to none'],
        ['c' => 'TempDatabase', 'm' => '__construct', 'n' => 'Changed default value for parameter $name in TempDatabase::__construct() from \'default\' to DB::CONN_PRIMARY'],
        ['c' => 'DBField', 'm' => 'scaffoldFormField', 'n' => 'Changed default value for parameter $params in DBField::scaffoldFormField() from null to []'],
        ['c' => 'SSViewer', 'm' => 'add_themes', 'n' => 'Changed default value for parameter $themes in SSViewer::add_themes() from [] to none'],
        ['c' => 'SSViewer', 'm' => 'set_themes', 'n' => 'Changed default value for parameter $themes in SSViewer::set_themes() from [] to none'],

        ['c' => 'Versioned', 'm' => 'augmentLoadLazyFields', 'n' => 'Changed default value for parameter $dataQuery in Versioned::augmentLoad_lazy_fields() from null to none'],
        ['c' => 'Versioned', 'm' => 'get_by_stage', 'n' => 'Changed default value for parameter $join in Versioned::get_by_stage() from \'\' to null'],

        ['c' => 'DataObjectVersionFormFactory', 'm' => 'getFormActions', 'n' => 'Changed default value for parameter $controller in DataObjectVersionFormFactory::getFormActions() from null to none'],
        ['c' => 'DataObjectVersionFormFactory', 'm' => 'getFormFields', 'n' => 'Changed default value for parameter $controller in DataObjectVersionFormFactory::getFormFields() from null to none'],


    ];
}
