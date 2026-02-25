<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6\AddNewParameterChanges;
use Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6\ChangedDefaultParameterValueChanges;
use Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6\ChangedParameterTypeChanges;
use Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6\ObsoleteMethodChanges;
use Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6\OtherMethodChanges;
use Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6\RemovedParameterChanges;
use Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6\RenamedParameterChanges;
use Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6\RenamedToChanges;
use Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6\ReplacedWithChanges;
use Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6\ReturnTypeChanges;
use Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6\UseInsteadChanges;
use Netwerkstatt\SilverstripeRector\Rector\Methods\AddNewParameter;
use Netwerkstatt\SilverstripeRector\Rector\Methods\ChangedDefaultParameterValue;
use Netwerkstatt\SilverstripeRector\Rector\Methods\ChangedParameterType;
use Netwerkstatt\SilverstripeRector\Rector\Methods\ObsoleteMethod;
use Netwerkstatt\SilverstripeRector\Rector\Methods\OtherMethod;
use Netwerkstatt\SilverstripeRector\Rector\Methods\RemovedParameter;
use Netwerkstatt\SilverstripeRector\Rector\Methods\RenamedParameter;
use Netwerkstatt\SilverstripeRector\Rector\Methods\RenamedTo;
use Netwerkstatt\SilverstripeRector\Rector\Methods\ReplacedWith;
use Netwerkstatt\SilverstripeRector\Rector\Methods\ReturnType;
use Netwerkstatt\SilverstripeRector\Rector\Methods\UseInstead;
use Rector\Config\RectorConfig;


return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();
    $rectorConfig->removeUnusedImports();
    $rectorConfig->ruleWithConfiguration(
        AddNewParameter::class,
        AddNewParameterChanges::get_list_cleaned()
    );
    $rectorConfig->ruleWithConfiguration(
        ChangedDefaultParameterValue::class,
        ChangedDefaultParameterValueChanges::get_list_cleaned()
    );
    $rectorConfig->ruleWithConfiguration(
        ChangedParameterType::class,
        ChangedParameterTypeChanges::get_list_cleaned()
    );
    $rectorConfig->ruleWithConfiguration(
        ObsoleteMethod::class,
        ObsoleteMethodChanges::get_list_cleaned()
    );
    $rectorConfig->ruleWithConfiguration(
        OtherMethod::class,
        OtherMethodChanges::get_list_cleaned()
    );
    $rectorConfig->ruleWithConfiguration(
        RemovedParameter::class,
        RemovedParameterChanges::get_list_cleaned()
    );
    $rectorConfig->ruleWithConfiguration(
        RenamedParameter::class,
        RenamedParameterChanges::get_list_cleaned()
    );
    $rectorConfig->ruleWithConfiguration(
        RenamedTo::class,
        RenamedToChanges::get_list_cleaned()
    );
    $rectorConfig->ruleWithConfiguration(
        ReplacedWith::class,
        ReplacedWithChanges::get_list_cleaned()
    );
    $rectorConfig->ruleWithConfiguration(
        ReturnType::class,
        ReturnTypeChanges::get_list_cleaned()
    );
    $rectorConfig->ruleWithConfiguration(
        UseInstead::class,
        UseInsteadChanges::get_list_cleaned()
    );
};
