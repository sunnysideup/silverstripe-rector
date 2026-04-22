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
    /**
     * Checks if an actual class is the same as or a subclass of a configured class.
     * 
     * Performs case-insensitive comparison and handles both fully qualified class names
     * and short class names. Uses reflection to check inheritance hierarchy.
     *
     * @param string $actualClass The actual class name to check
     * @param string $configuredClass The configured class name to match against
     * @return bool True if the actual class matches or is a subclass of the configured class
     */
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

    /**
     * Builds a TODO comment line with class and method information.
     * 
     * Creates a formatted TODO line for Silverstripe upgrades, displaying the short
     * class name with the full FQCN in parentheses if different. Includes method name
     * if provided.
     *
     * @param string $className The fully qualified class name
     * @param string $methodName The method name (empty string if not applicable)
     * @param string $note Additional note or message for the TODO
     * @return string The formatted TODO line
     */
    private function buildTodoLine(string $className, string $methodName, string $note): string
    {
        $parts = explode('\\', ltrim($className, '\\'));
        $displayClass = (string) end($parts);
        if ($displayClass !== $className) {
            $note .= " FQCN: ({$className})";
        }
        if ($methodName === '') {
            return trim(sprintf('@TODO SSU RECTOR UPGRADE TASK - %s: %s', $displayClass, $note));
        }

        return trim(sprintf('@TODO SSU RECTOR UPGRADE TASK - %s::%s: %s', $displayClass, $methodName, $note));
    }

    /**
     * Safely appends a TODO comment to a node with idempotency check.
     * 
     * Adds a line comment to the node only if the same TODO doesn't already exist.
     * Preserves existing comments and appends the new comment to the array.
     *
     * @param Node $node The AST node to add the comment to
     * @param string $todoLine The TODO text to add
     * @return bool True if the comment was added, false if it already exists
     */
    private function appendTodoDocCommentSafely(Node $node, string $todoLine): bool
    {
        // Get all existing comments (both // and /** */)
        $comments = $node->getComments();

        // Idempotency check: Don't add the same TODO if it already exists
        foreach ($comments as $comment) {
            if (str_contains($comment->getText(), $todoLine)) {
                return false;
            }
        }

        // Create the new line comment
        // Note: We use \PhpParser\Comment for // and \PhpParser\Comment\Doc for /** */
        $newComment = new \PhpParser\Comment('// ' . $todoLine);

        // Add the new comment to the array
        $comments[] = $newComment;

        // Re-attach the updated comments array to the node
        $node->setAttribute('comments', $comments);

        return true;
    }

    /**
     * Checks if a PHPStan type is unknown or indeterminate.
     * 
     * Identifies types that cannot be reliably analyzed (MixedType, ErrorType,
     * or ObjectWithoutClassType).
     *
     * @param Type $type The PHPStan type to check
     * @return bool True if the type is unknown
     */
    private function isUnknownType(Type $type): bool
    {
        return $type instanceof MixedType
            || $type instanceof ErrorType
            || $type instanceof ObjectWithoutClassType;
    }
    
    /**
     * Extracts the method name from a method call, nullsafe method call, or static call.
     * 
     * Returns the method name as a string if it's an Identifier, otherwise returns null
     * for dynamic method calls.
     *
     * @param MethodCall|NullsafeMethodCall|StaticCall $call The method call node
     * @return string|null The method name or null if it cannot be resolved
     */
    private function resolveCalledMethodName(MethodCall|NullsafeMethodCall|StaticCall $call): ?string
    {
        if ($call->name instanceof Identifier) {
            return $call->name->toString();
        }
        return null;
    }

    /**
     * Main refactor method that delegates to specific refactoring methods.
     * 
     * Routes the node to either refactorExpression or refactorClassMethod based
     * on the node type.
     *
     * @param Expression|ClassMethod $node The node to refactor
     * @return Node|null The refactored node if changes were made, null otherwise
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Expression) {
            return $this->refactorExpression($node);
        }

        return $this->refactorClassMethod($node);
    }

    /**
     * Checks if a method call matches the configured target class.
     * 
     * For static calls, checks the class name directly. For instance calls, resolves
     * the receiver type and matches it against the configured class. Handles unknown
     * types based on the 'u' flag in the change configuration.
     *
     * @param MethodCall|NullsafeMethodCall|StaticCall $call The method call to check
     * @param array $change The change configuration containing class, method, and note
     * @return bool True if the call target matches the configured class
     */
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
            return (bool) ($change['u'] ?? true);  // Changed: default to TRUE
        }

        return $this->matchesTypeAgainstConfiguredClass($receiverType, (string) $change['c']);
    }

    /**
     * Recursively matches a PHPStan type against a configured class name.
     * 
     * Handles UnionTypes by checking each constituent type. For ObjectTypes,
     * delegates to isClassSameOrSubclassOfConfigured for inheritance checking.
     *
     * @param Type $type The PHPStan type to match
     * @param string $configuredClass The configured class name to match against
     * @return bool True if the type matches the configured class
     */
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

    /**
     * Configures the rector rule with an array of method changes.
     * 
     * Normalizes the configuration array, ensuring each change has 'c' (class),
     * 'm' (method), 'n' (note), and optionally 'u' (unknown type flag) keys.
     *
     * @param array $configuration Array of change configurations
     * @return void
     */
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

    /**
     * Refactors Expression nodes containing method calls by adding TODO comments.
     * 
     * Checks if the expression contains a method call that matches any configured changes.
     * If a match is found, adds a TODO comment. Optionally renames the method if withRename
     * is true and a new method name can be extracted from the note.
     *
     * @param Expression $expression The expression node to refactor
     * @param bool|null $withRename Whether to attempt automatic method renaming
     * @return Node|null The refactored expression if changes were made, null otherwise
     */
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

    /**
     * Returns the AST node types that this rector rule operates on.
     * 
     * Targets Expression nodes (for method call sites) and ClassMethod nodes
     * (for method declarations).
     *
     * @return array<class-string<Node>> Array of node class names
     */
    public function getNodeTypes(): array
    {
        // Target Expression (so comments go *above* the statement) and ClassMethod
        return [
            Expression::class,
            ClassMethod::class,
        ];
    }

    /**
     * Refactors ClassMethod nodes by adding TODO comments and optionally renaming.
     * 
     * Checks if the class method belongs to a class that matches any configured changes.
     * If a match is found, adds a TODO comment. Optionally renames the method if withRename
     * is true and a strict new method name can be extracted.
     *
     * @param ClassMethod $classMethod The class method node to refactor
     * @param bool|null $withRename Whether to attempt automatic method renaming
     * @return Node|null The refactored class method if changes were made, null otherwise
     */
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

    /**
     * Alternative version of refactorClassMethod without the withRename parameter.
     * 
     * Refactors ClassMethod nodes by adding TODO comments and attempting strict method
     * renaming. This appears to be a duplicate method with hardcoded behavior.
     *
     * @param ClassMethod $classMethod The class method node to refactor
     * @return Node|null The refactored class method if changes were made, null otherwise
     */
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