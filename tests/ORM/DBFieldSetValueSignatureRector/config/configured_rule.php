<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\ORM\DBFieldSetValueSignatureRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        DBFieldSetValueSignatureRector::class,
    ]);
