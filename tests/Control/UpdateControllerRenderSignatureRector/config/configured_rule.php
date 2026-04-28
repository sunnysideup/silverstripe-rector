<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\Control\UpdateControllerRenderSignatureRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        UpdateControllerRenderSignatureRector::class,
    ]);
