<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Tests\Injector\UseCreateRectorParenthesizedNew;

use PHPUnit\Framework\Attributes\DataProvider;

/**
 * UseCreateRector combined with Rector's PHP 8.4 NewMethodCallWithoutParenthesesRector must not break
 * `(new Foo())->bar()` (the combination used to leave an unbalanced closing parenthesis).
 */
class UseCreateRectorParenthesizedNewTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        require_once __DIR__ . '/../UseCreateRector/Source/InjectableClass.php';
        require_once __DIR__ . '/../UseCreateRector/Source/InjectableSubClass.php';

        $this->doTestFile($filePath);
    }

    public static function provideData(): \Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
