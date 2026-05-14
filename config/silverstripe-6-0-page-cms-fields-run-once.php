<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\DataObject\DataObjectStaticMethodsToFluentRector;

use Netwerkstatt\SilverstripeRector\Rector\BuildTask\PolyCommandGetOptionsPublicRector;
use Netwerkstatt\SilverstripeRector\Rector\CMS\AddScaffoldCmsFieldsSettingsRector;
use Netwerkstatt\SilverstripeRector\Rector\ORM\RemoveEmptyFilterRector;
use Netwerkstatt\SilverstripeRector\Tests\ORM\RemoveEmptyFilterRector\RemoveEmptyFilterRectorTest;
use Rector\Config\RectorConfig;


return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddScaffoldCmsFieldsSettingsRector::class);
};
