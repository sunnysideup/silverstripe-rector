<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\CMS\AddScaffoldCmsFieldsSettingsRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddScaffoldCmsFieldsSettingsRector::class);
};
