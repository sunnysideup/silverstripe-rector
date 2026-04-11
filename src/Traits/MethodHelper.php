<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Traits;

use PhpParser\Node;
use PhpParser\Comment\Doc;
use PHPStan\Type\Type;
use PHPStan\Type\ErrorType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectWithoutClassType;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\PHPStan\ScopeFetcher;
use PHPStan\Type\ObjectType;
use PHPStan\Type\UnionType;

trait MethodHelper
{
    private function isClassSameOrSubclassOfConfigured(string $actualClass, string $configuredClass): bool
    {
        $actualClass = ltrim($actualClass, '\\');
        $configuredClass = ltrim($configuredClass, '\\');

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

        foreach (array_merge([$classReflection->getName()], $classReflection->getParentClassesNames()) as $candidate) {
            if (!is_string($candidate) || $candidate === '') {
                continue;
            }

            if (
                str_ends_with(strtolower($candidate), '\\' . strtolower($configuredClass)) ||
                strcasecmp($candidate, $configuredClass) === 0
            ) {
                return true;
            }
        }

        return false;
    }
    private function buildTodoLine(string $className, string $methodName, string $note): string
    {
        $parts = explode('\\', ltrim($className, '\\'));
        $displayClass = (string) end($parts);
        if ($displayClass !== $className) {
            $note .= " FQCN: ({$className})";
        }
        if ($methodName === '') {
            return sprintf('@TODO SSU RECTOR UPGRADE TASK - %s: %s', $displayClass, $note);
        }

        return sprintf('@TODO SSU RECTOR UPGRADE TASK - %s::%s: %s', $displayClass, $methodName, $note);
    }
    private function appendTodoDocCommentSafely(Node $node, string $todoLine): bool
    {
        $comments = $node->getComments();

        // Idempotency check across all comments
        foreach ($comments as $comment) {
            if (str_contains($comment->getText(), $todoLine)) {
                return false;
            }
        }

        $existingDoc = $node->getDocComment();

        if ($existingDoc instanceof Doc) {
            $text = $existingDoc->getText();
            $trimmed = rtrim($text);

            // If it's a standard docblock ending in */
            if (str_ends_with($trimmed, '*/')) {
                $trimmed = substr($trimmed, 0, -2);
                $newDocText = rtrim($trimmed) . "\n * " . $todoLine . "\n */";
            } else {
                $newDocText = $text . "\n * " . $todoLine;
            }
        } else {
            // Create a fresh multi-line docblock
            $newDocText = "/**\n * " . $todoLine . "\n */";
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
    private function resolveCalledMethodName(MethodCall|NullsafeMethodCall|StaticCall $call): ?string
    {
        if ($call->name instanceof Identifier) {
            return $call->name->toString();
        }
        return null;
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

    private function refactorExpression(Expression $expression, ?bool $withRename = false): ?Node
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
            if ($withRename) {
                // Attempt auto-fix renaming
                $newName = $this->extractNewMethodName($change['n']);
                if ($newName !== null && $newName !== $methodName) {
                    $expr->name = new Identifier($newName);
                    $changed = true;
                }
            }
        }

        return $changed ? $expression : null;
    }

    public function getNodeTypes(): array
    {
        // Target Expression (so comments go *above* the statement) and ClassMethod
        return [
            Expression::class,
            ClassMethod::class,
        ];
    }

    private function refactorClassMethod(ClassMethod $classMethod, ?bool $withRename = false): ?Node
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

            // Target C: Class method declaration overrides (is subclass or exact match)
            if (!$this->isClassSameOrSubclassOfConfigured($currentClassName, (string) $change['c'])) {
                continue;
            }

            $todoLine = $this->buildTodoLine($change['c'], $change['m'], $change['n']);

            if ($this->appendTodoDocCommentSafely($classMethod, $todoLine)) {
                $changed = true;
            }
            if ($withRename) {
                // Attempt strict, conservative auto-fix
                $newName = $this->extractStrictNewMethodName($change['n']);
                if ($newName !== null && $newName !== $methodName) {
                    $classMethod->name = new Identifier($newName);
                    $changed = true;
                }
            }
        }

        return $changed ? $classMethod : null;
    }


    private function refactorClassMethod2(ClassMethod $classMethod): ?Node
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

            // Attempt strict, conservative auto-fix
            $newName = $this->extractStrictNewMethodName($change['n']);
            if ($newName !== null && $newName !== $methodName) {
                $classMethod->name = new Identifier($newName);
                $changed = true;
            }
        }

        return $changed ? $classMethod : null;
    }
}
