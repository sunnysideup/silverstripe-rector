<?php

declare(strict_types=1);

use Netwerkstatt\SilverstripeRector\Rector\ORM\DBCompositeWriteToManipulationSignatureRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        DBCompositeWriteToManipulationSignatureRector::class,
    ]);
