<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\Methods;

use Netwerkstatt\SilverstripeRector\Traits\MethodHelper;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\PHPStan\ScopeFetcher;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ErrorType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ChangedDefaultParameterValue extends AbstractRector implements ConfigurableRectorInterface
{
    use MethodHelper;
    /**
     * @var array<int, array{c: string, m: string, n: string, u?: bool}>
     */
    private array $changes = [];

    public function __construct(
        private readonly ReflectionProvider $reflectionProvider
    ) {}

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Adds TODO upgrade comments for method calls/overrides where a parameter default value changed.',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
$service->doThing($value);
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
/** @TODO SSU RECTOR UPGRADE TASK - SomeClass::doThing: Changed default value of parameter $mode in SomeClass::doThing() from null to 'strict' */
$service->doThing($value);
CODE_SAMPLE,
                    [['c' => 'SomeClass', 'm' => 'doThing', 'n' => 'Changed default value of parameter $mode in SomeClass::doThing() from null to \'strict\'', 'u' => false]]
                ),
            ]
        );
    }

    // private function refactorClassMethod(ClassMethod $classMethod): ?Node - see Trait

    // private function matchesCallTarget(MethodCall|NullsafeMethodCall|StaticCall $call, array $change): bool - see Trait

    // private function resolveCalledMethodName(MethodCall|NullsafeMethodCall|StaticCall $call): ?string - see Trait

    // private function appendTodoDocCommentSafely(Node $node, string $todoLine): bool - see Trait

    // private function isUnknownType(Type $type): bool - see Trait

    // private function matchesTypeAgainstConfiguredClass(Type $type, string $configuredClass): bool

    // public function configure(array $configuration): void - see Trait

    // public function getNodeTypes(): array - see Trait

    // public function refactor(Node $node): ?Node - see Trait

    // private function refactorExpression(Expression $expression): ?Node

}
