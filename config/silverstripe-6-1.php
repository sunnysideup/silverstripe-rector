<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\DataObject\DataObjectStaticMethodsToFluentRector;

use Netwerkstatt\SilverstripeRector\Rector\BuildTask\PolyCommandGetOptionsPublicRector;
use Netwerkstatt\SilverstripeRector\Tests\ORM\RemoveEmptyFilterRector\RemoveEmptyFilterRectorTest;
use Rector\Config\RectorConfig;


return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();
    $rectorConfig->removeUnusedImports();
    $rectorConfig->rule(DataObjectStaticMethodsToFluentRector::class);
    $rectorConfig->rule(PolyCommandGetOptionsPublicRector::class); // this is after you have manually upgraded the buildTask.
    $rectorConfig->rule(RemoveEmptyFilterRectorTest::class); // this is after you have manually upgraded the buildTask.
};
