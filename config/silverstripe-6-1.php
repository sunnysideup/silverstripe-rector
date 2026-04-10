<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\BuildTask\BuildTaskTitlePropertyRector;
use Netwerkstatt\SilverstripeRector\Rector\DataObject\DataObjectStaticMethodsToFluentRector;

use Netwerkstatt\SilverstripeRector\Rector\BuildTask\PolyCommandGetOptionsPublicRector;
use Netwerkstatt\SilverstripeRector\Rector\ORM\DataObjectGetToClassGetRector;
use Rector\Config\RectorConfig;


return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();
    $rectorConfig->removeUnusedImports();
    $rectorConfig->rule(DataObjectStaticMethodsToFluentRector::class);
    $rectorConfig->rule(PolyCommandGetOptionsPublicRector::class);
    $rectorConfig->rule(BuildTaskTitlePropertyRector::class);
    $rectorConfig->rule(DataObjectGetToClassGetRector::class);
};
