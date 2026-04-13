<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\CMS\RenameCurrentPageIdToCurrentRecordIdRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        RenameCurrentPageIdToCurrentRecordIdRector::class,
    ]);
