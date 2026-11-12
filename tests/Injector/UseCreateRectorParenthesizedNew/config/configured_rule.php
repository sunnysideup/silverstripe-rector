<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Tests\Injector\UseCreateRectorParenthesizedNew\config;

use Netwerkstatt\SilverstripeRector\Rector\Injector\UseCreateRector;
use Rector\Config\RectorConfig;
use Rector\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersion::PHP_84);
    $rectorConfig->rule(NewMethodCallWithoutParenthesesRector::class);
    $rectorConfig->rule(UseCreateRector::class);
};
