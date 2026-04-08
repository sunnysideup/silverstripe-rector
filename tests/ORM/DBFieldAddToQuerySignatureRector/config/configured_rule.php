<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\ORM\DBFieldAddToQuerySignatureRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        DBFieldAddToQuerySignatureRector::class,
    ]);
