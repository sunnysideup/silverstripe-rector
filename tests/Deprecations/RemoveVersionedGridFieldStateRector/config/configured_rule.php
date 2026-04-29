<?php

declare(strict_types=1);

use App\Rector\Deprecations\RemoveVersionedGridFieldStateRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        RemoveVersionedGridFieldStateRector::class,
    ]);
