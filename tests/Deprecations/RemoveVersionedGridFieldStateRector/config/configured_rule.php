<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Deprecations\RemoveVersionedGridFieldStateRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        RemoveVersionedGridFieldStateRector::class,
    ]);
