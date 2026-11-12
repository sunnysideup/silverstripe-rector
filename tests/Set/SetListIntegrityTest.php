<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Tests\Set;

use Netwerkstatt\SilverstripeRector\Set\SilverstripeLevelSetList;
use Netwerkstatt\SilverstripeRector\Set\SilverstripeSetList;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rector\Config\RectorConfig;
use ReflectionClass;

/**
 * Guards the public set list API: every constant must point to an existing config file
 * that loads and can be applied to a RectorConfig (e.g. no syntax errors in a ChangeList).
 */
final class SetListIntegrityTest extends TestCase
{
    #[DataProvider('provideSetFiles')]
    public function testSetFileLoads(string $constant, string $path): void
    {
        $this->assertFileExists($path, $constant . ' points to a missing file');

        $config = require $path;
        $this->assertIsCallable($config, $constant . ' must return a callable');

        $config(new RectorConfig());
    }

    public static function provideSetFiles(): \Iterator
    {
        foreach ([SilverstripeSetList::class, SilverstripeLevelSetList::class] as $class) {
            foreach ((new ReflectionClass($class))->getConstants() as $name => $path) {
                yield $class . '::' . $name => [$class . '::' . $name, $path];
            }
        }
    }
}
