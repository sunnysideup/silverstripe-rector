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

final class Visibility extends AbstractRector implements ConfigurableRectorInterface
{
    use MethodHelper;
    /**
     * @var array<int, array{c: string, m: string, from: string, to: string, n: string, u?: bool}>
     */
    private array $changes = [];

    public function __construct(
        private readonly ReflectionProvider $reflectionProvider
    ) {}

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Adds TODO upgrade comments for method calls/overrides where the method visibility changed.',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
$leftAndMain->jsonError($message, 400);
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
/** @TODO SSU RECTOR UPGRADE TASK - LeftAndMain::jsonError: Changed visibility for method LeftAndMain::jsonError() from public to protected */
$leftAndMain->jsonError($message, 400);
CODE_SAMPLE,
                    [['c' => 'LeftAndMain', 'm' => 'jsonError', 'from' => 'public', 'to' => 'protected', 'n' => 'Changed visibility for method LeftAndMain::jsonError() from public to protected', 'u' => false]]
                ),
            ]
        );
    }

    public function configure(array $configuration): void
    {
        $this->changes = [];
        foreach ($configuration as $item) {
            if (!isset($item['c'], $item['m'], $item['n'])) {
                continue;
            }

            $this->changes[] = [
                'c' => (string) $item['c'],
                'm' => (string) $item['m'],
                'from' => (string) ($item['from'] ?? ''),
                'to' => (string) ($item['to'] ?? ''),
                'n' => (string) $item['n'],
                'u' => (bool) ($item['u'] ?? false),
            ];
        }
    }

    public function getNodeTypes(): array
    {
        return [
            Expression::class,
            ClassMethod::class,
        ];
    }

    /**
     * @param Expression|ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Expression) {
            return $this->refactorExpression($node);
        }

        return $this->refactorClassMethod($node);
    }

    private function refactorExpression(Expression $expression): ?Node
    {
        $expr = $expression->expr;

        if (!$expr instanceof MethodCall && !$expr instanceof NullsafeMethodCall && !$expr instanceof StaticCall) {
            return null;
        }

        $methodName = $this->resolveCalledMethodName($expr);
        if ($methodName === null) {
            return null;
        }

        $changed = false;

        foreach ($this->changes as $change) {
            if (strcasecmp($change['m'], $methodName) !== 0) {
                continue;
            }

            if (!$this->matchesCallTarget($expr, $change)) {
                continue;
            }

            $todoLine = $this->buildTodoLine($change['c'], $change['m'], $change['n']);

            if ($this->appendTodoDocCommentSafely($expression, $todoLine)) {
                $changed = true;
            }
        }

        return $changed ? $expression : null;
    }

    private function refactorClassMethod(ClassMethod $classMethod): ?Node
    {
        if (!$classMethod->name instanceof Identifier) {
            return null;
        }

        // Fetch scope dynamically here
        $scope = ScopeFetcher::fetch($classMethod);
        $classReflection = $scope->getClassReflection();

        if ($classReflection === null) {
            return null;
        }

        $methodName = $classMethod->name->toString();
        $currentClassName = $classReflection->getName();

        $changed = false;

        foreach ($this->changes as $change) {
            if (strcasecmp($change['m'], $methodName) !== 0) {
                continue;
            }

            if (!$this->isClassSameOrSubclassOfConfigured($currentClassName, (string) $change['c'])) {
                continue;
            }

            $todoLine = $this->buildTodoLine($change['c'], $change['m'], $change['n']);

            if ($this->appendTodoDocCommentSafely($classMethod, $todoLine)) {
                $changed = true;
            }
        }

        return $changed ? $classMethod : null;
    }

    private function matchesCallTarget(MethodCall|NullsafeMethodCall|StaticCall $call, array $change): bool
    {
        if ($call instanceof StaticCall) {
            $classNode = $call->class;
            if ($classNode instanceof Name) {
                return $this->isClassSameOrSubclassOfConfigured($classNode->toString(), (string) $change['c']);
            }
            return false;
        }

        $receiverType = $this->getType($call->var);

        if ($this->isUnknownType($receiverType)) {
            return (bool) ($change['u'] ?? false);
        }

        return $this->matchesTypeAgainstConfiguredClass($receiverType, (string) $change['c']);
    }

    private function resolveCalledMethodName(MethodCall|NullsafeMethodCall|StaticCall $call): ?string
    {
        if ($call->name instanceof Identifier) {
            return $call->name->toString();
        }
        return null;
    }

    private function buildTodoLine(string $className, string $methodName, string $note): string
    {
        $parts = explode('\\', ltrim($className, '\\'));
        $displayClass = (string) end($parts);

        return sprintf('@TODO SSU RECTOR UPGRADE TASK - %s::%s: %s', $displayClass, $methodName, $note);
    }

    private function appendTodoDocCommentSafely(Node $node, string $todoLine): bool
    {
        $comments = $node->getComments();

        foreach ($comments as $comment) {
            if (str_contains($comment->getText(), $todoLine)) {
                return false;
            }
        }

        $existingDoc = $node->getDocComment();
        $newDocText = '/** ' . $todoLine . ' */';

        if ($existingDoc instanceof Doc) {
            $text = $existingDoc->getText();
            $trimmed = rtrim($text);

            if (str_ends_with($trimmed, '*/')) {
                $trimmed = substr($trimmed, 0, -2);
                $newDocText = rtrim($trimmed) . "\n * " . $todoLine . "\n */";
            } else {
                $newDocText = $text . "\n" . $newDocText;
            }
        }

        $node->setDocComment(new Doc($newDocText));
        return true;
    }

    private function isUnknownType(Type $type): bool
    {
        return $type instanceof MixedType
            || $type instanceof ErrorType
            || $type instanceof ObjectWithoutClassType;
    }

    private function matchesTypeAgainstConfiguredClass(Type $type, string $configuredClass): bool
    {
        if ($type instanceof UnionType) {
            foreach ($type->getTypes() as $subType) {
                if ($this->matchesTypeAgainstConfiguredClass($subType, $configuredClass)) {
                    return true;
                }
            }
            return false;
        }

        if (!$type instanceof ObjectType) {
            return false;
        }

        return $this->isClassSameOrSubclassOfConfigured($type->getClassName(), $configuredClass);
    }
}
