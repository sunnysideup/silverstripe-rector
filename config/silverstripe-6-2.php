<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\ORM\GetIDListToColumnIDRector;
use Rector\Config\RectorConfig;
use Rector\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('SilverStripe\Forms\FieldList', 'dataFields', 'getDataFields'),
    ]);
    $rectorConfig->rule(GetIDListToColumnIDRector::class);
    // This rector causes issues with the SpatieEnumMethodCallToEnumConstRector, so we skip it for now until we can find a solution.
    // HERE IS THE ERROR WE GET:
    // -        $records = $ownerClass::get()->where(implode(' OR ', $where));
    // +        $records = ownerClass::GET->where(implode(' OR ', $where));
    $rectorConfig->skip([
        SpatieEnumMethodCallToEnumConstRector::class,
    ]);
};
