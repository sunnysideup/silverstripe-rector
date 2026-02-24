<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\Methods;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ErrorType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddNewParameter extends AbstractRector implements ConfigurableRectorInterface
{
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
            'Adds TODO upgrade comments for method calls/overrides where a new parameter was added.',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
$service->run($request);
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
/** @TODO UPGRADE TASK - BuildTask::run: Added new parameter $output in BuildTask::run() */
$service->run($request);
CODE_SAMPLE,
                    [['c' => 'BuildTask', 'm' => 'run', 'n' => 'Added new parameter $output in BuildTask::run()', 'u' => false]]
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

        $methodName = $classMethod->name->toString();
        $currentClassName = $this->resolveCurrentClassName($classMethod);

        if ($currentClassName === null) {
            return null;
        }

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
            return $this->matchesStaticCallClass($call, (string) $change['c']);
        }

        $receiverType = $this->getType($call->var);

        if ($this->isUnknownType($receiverType)) {
            return (bool) ($change['u'] ?? false);
        }

        return $this->matchesTypeAgainstConfiguredClass($receiverType, (string) $change['c']);
    }

    private function matchesStaticCallClass(StaticCall $staticCall, string $configuredClass): bool
    {
        $classNode = $staticCall->class;

        if ($classNode instanceof Name) {
            $calledClass = $classNode->toString();
            return $this->isClassSameOrSubclassOfConfigured($calledClass, $configuredClass);
        }

        return false;
    }

    private function resolveCalledMethodName(MethodCall|NullsafeMethodCall|StaticCall $call): ?string
    {
        if ($call->name instanceof Identifier) {
            return $call->name->toString();
        }
        return null;
    }

    private function resolveCurrentClassName(ClassMethod $classMethod): ?string
    {
        $className = $classMethod->getAttribute(AttributeKey::CLASS_NAME);
        if (is_string($className) && $className !== '') {
            return $className;
        }

        /** @var Class_|null $classLike */
        $classLike = $classMethod->getAttribute(AttributeKey::PARENT_NODE);
        if ($classLike instanceof Class_ && $classLike->name instanceof Identifier) {
            return $classLike->name->toString();
        }

        return null;
    }

    private function buildTodoLine(string $className, string $methodName, string $note): string
    {
        $displayClass = $this->shortClassName($className);
        return sprintf('@TODO UPGRADE TASK - %s::%s: %s', $displayClass, $methodName, $note);
    }

    private function shortClassName(string $className): string
    {
        $parts = explode('\\', ltrim($className, '\\'));
        return (string) end($parts);
    }

    /**
     * Appends a Doc comment properly using PHP-Parser's Comment arrays.
     * Returns true if changed, false if it already existed.
     */
    private function appendTodoDocCommentSafely(Node $node, string $todoLine): bool
    {
        $comments = $node->getComments();

        // Idempotency check across all existing comments
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
                // Strip the closing tags, append our line, and re-close it nicely
                $trimmed = substr($trimmed, 0, -2);
                $newDocText = rtrim($trimmed) . "\n * " . $todoLine . "\n */";
            } else {
                $newDocText = $text . "\n" . $newDocText;
            }

            // Remove the old docblock from the stack
            $comments = array_filter($comments, fn($c) => $c !== $existingDoc);
        }

        $comments[] = new Doc($newDocText);
        $node->setAttribute(AttributeKey::COMMENTS, array_values($comments));

        return true;
    }

    private function isUnknownType(Type $type): bool
    {
        // Properly check against PHPStan's base unknown/mixed types
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

    private function isClassSameOrSubclassOfConfigured(string $actualClass, string $configuredClass): bool
    {
        $actualClass = ltrim($actualClass, '\\');
        $configuredClass = ltrim($configuredClass, '\\');

        // Exact or suffix match
        if (
            strcasecmp($actualClass, $configuredClass) === 0 ||
            str_ends_with(strtolower($actualClass), '\\' . strtolower($configuredClass))
        ) {
            return true;
        }

        if (!$this->reflectionProvider->hasClass($actualClass)) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($actualClass);

        if (str_contains($configuredClass, '\\')) {
            return $classReflection->isSubclassOf($configuredClass);
        }

        // Short-name fallback loop over parent chain
        foreach (array_merge([$classReflection->getName()], array_keys($classReflection->getParentClassesNames())) as $candidate) {
            if (
                str_ends_with(strtolower($candidate), '\\' . strtolower($configuredClass)) ||
                strcasecmp($candidate, $configuredClass) === 0
            ) {
                return true;
            }
        }

        return false;
    }
}
