<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\CMS\AddScaffoldCmsFieldsSettingsRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        AddScaffoldCmsFieldsSettingsRector::class,
    ]);
