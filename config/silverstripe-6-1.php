<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\BuildTask\BuildTaskTitlePropertyRector;
use Netwerkstatt\SilverstripeRector\Rector\DataObject\DataObjectStaticMethodsToFluentRector;
use Netwerkstatt\SilverstripeRector\Rector\ORM\RemoveEmptyFilterRector;
use Netwerkstatt\SilverstripeRector\Tests\ORM\RemoveEmptyFilterRector\RemoveEmptyFilterRectorTest;
use Netwerkstatt\SilverstripeRector\Rector\BuildTask\PolyCommandGetOptionsPublicRector;
use Netwerkstatt\SilverstripeRector\Rector\ORM\DataObjectGetToClassGetRector;
use Rector\Config\RectorConfig;
use Netwerkstatt\SilverstripeRector\Tests\ORM\RemoveEmptyFilterRector\RemoveEmptyFilterRectorTest;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();
    $rectorConfig->removeUnusedImports();
    $rectorConfig->rule(DataObjectStaticMethodsToFluentRector::class);

    $rectorConfig->rule(PolyCommandGetOptionsPublicRector::class); // this is after you have manually upgraded the buildTask.
    $rectorConfig->rule(RemoveEmptyFilterRectorTest::class); // this is after you have manually upgraded the buildTask.
    $rectorConfig->ruleWithConfiguration(SilverstripeDeprecationCommentRector::class, [
        'SilverStripe\UserForms\Extension\UpgradePolymorphicExtension' => [
            'message' => 'UpgradePolymorphicExtension has been deprecated and will be removed without equivalent functionality',
            'link' => 'https://docs.silverstripe.org/en/6/changelogs/6.1.0/#api-changes',
        ],
    ]);
    $rectorConfig->rule(BuildTaskTitlePropertyRector::class);
    $rectorConfig->rule(DataObjectGetToClassGetRector::class);
    $rectorConfig->rule(RemoveEmptyFilterRector::class); // this is after you have manually upgraded the buildTask.
}
};
