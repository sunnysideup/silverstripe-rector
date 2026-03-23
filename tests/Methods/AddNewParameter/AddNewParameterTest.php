<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\AddNewParameter;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class AddNewParameterTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        // This tells the test exactly which Rector config to use
        return __DIR__ . '/config/configured_rule.php';
    }
}