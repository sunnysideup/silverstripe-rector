<?php


declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6;

use Netwerkstatt\SilverstripeRector\Interfaces\ChangeListInterface;
use Netwerkstatt\SilverstripeRector\Traits\MethodChangeHelper;

class AddNewParameterChanges implements ChangeListInterface
{

    use MethodChangeHelper;

    private const LIST = [
        ['c' => 'ReorderElements', 'm' => '__construct', 'n' => 'Added new parameter $elementIsNew in ReorderElements::__construct()'],

        ['c' => 'Image_Backend', 'm' => 'crop', 'n' => 'Added new parameter $backgroundColour in Image_Backend::crop()'],
        ['c' => 'Image_Backend', 'm' => 'crop', 'n' => 'Added new parameter $position in Image_Backend::crop()'],
        ['c' => 'Image_Backend', 'm' => 'croppedResize', 'n' => 'Added new parameter $position in Image_Backend::croppedResize()'],

        ['c' => 'CMSSiteTreeFilter', 'm' => 'getFilteredPages', 'n' => 'Added new parameter $list in CMSSiteTreeFilter::getFilteredPages()'],
        ['c' => 'VirtualPage', 'm' => 'castingHelper', 'n' => 'Added new parameter $useFallback in VirtualPage::castingHelper()'],

        ['c' => 'ChangePasswordHandler', 'm' => 'setSessionToken', 'n' => 'Added new parameter $alreadyEncrypted in ChangePasswordHandler::setSessionToken()'],
        ['c' => 'DataList', 'm' => 'excludeAny', 'n' => 'Added new parameter $args in DataList::excludeAny()'],
        ['c' => 'DataQuery', 'm' => 'conjunctiveGroup', 'n' => 'Added new parameter $clause in DataQuery::conjunctiveGroup()'],
        ['c' => 'DataQuery', 'm' => 'disjunctiveGroup', 'n' => 'Added new parameter $clause in DataQuery::disjunctiveGroup()'],
        ['c' => 'MoneyField', 'm' => 'buildCurrencyField', 'n' => 'Added new parameter $forceTextField in MoneyField::buildCurrencyField()'],
        ['c' => 'DBDate', 'm' => 'Format', 'n' => 'Added new parameter $locale in DBDate::Format()'],
        ['c' => 'BuildTask', 'm' => 'run', 'n' => 'Added new parameter $output in BuildTask::run()'],
        ['c' => 'Convert', 'm' => 'linkIfMatch', 'n' => 'Added new parameter $protocols in Convert::linkIfMatch()'],
        ['c' => 'DateField', 'm' => 'tidyInternal', 'n' => 'Added new parameter $returnNullOnFailure in DateField::tidyInternal()'],
        ['c' => 'DatetimeField', 'm' => 'tidyInternal', 'n' => 'Added new parameter $returnNullOnFailure in DatetimeField::tidyInternal()'],
        ['c' => 'TimeField', 'm' => 'tidyInternal', 'n' => 'Added new parameter $returnNullOnFailure in TimeField::tidyInternal()'],
        ['c' => 'Cookie', 'm' => 'force_expiry', 'n' => 'Added new parameter $sameSite in Cookie::force_expiry()'],
        ['c' => 'Cookie', 'm' => 'set', 'n' => 'Added new parameter $sameSite in Cookie::set()'],
        ['c' => 'CookieJar', 'm' => 'outputCookie', 'n' => 'Added new parameter $sameSite in CookieJar::outputCookie()'],
        ['c' => 'Cookie_Backend', 'm' => 'forceExpiry', 'n' => 'Added new parameter $sameSite in Cookie_Backend::forceExpiry()'],
        ['c' => 'Cookie_Backend', 'm' => 'set', 'n' => 'Added new parameter $sameSite in Cookie_Backend::set()'],
        ['c' => 'DataObject', 'm' => 'preWrite', 'n' => 'Added new parameter $skipValidation in DataObject::preWrite()'],
        ['c' => 'DataObject', 'm' => 'validateWrite', 'n' => 'Added new parameter $skipValidation in DataObject::validateWrite()'],

    ];
}
