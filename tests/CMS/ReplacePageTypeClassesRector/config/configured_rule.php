<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\CMS\ReplacePageTypeClassesRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        ReplacePageTypeClassesRector::class,
    ]);
