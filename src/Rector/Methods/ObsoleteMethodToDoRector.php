<?php

declare(strict_types=1);


namespace Netwerkstatt\SilverstripeRector\Rector\Methods;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ObsoleteMethodTodoRector extends AbstractRector implements ConfigurableRectorInterface
{
    public const METHODS = 'methods';

    /**
     * @var string
     */
    private const TODO_TEXT = '/** @TODO UPGRADE TASK - this method may no longer be available */';

    /**
     * Each entry:
     * - c: FQCN where method was removed
     * - m: method name
     * - n: note/explanation (optional, used in comment if you want)
     * - u: unique name bool (optional; if true, we may add TODO even when type is unknown)
     *
     * @var array<int, array{c: string, m: string, n?: string, u?: bool}>
     */
    private array $methods = [];

    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
    ) {
        // Intentionally empty; config provides methods.
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Adds TODO comments for calls/overrides of removed methods');
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [
            MethodCall::class,
            NullsafeMethodCall::class,
            StaticCall::class,
            ClassMethod::class,
        ];
    }

    /**
     * @param array<string, mixed> $configuration
     */
    public function configure(array $configuration): void
    {
        $methods = $configuration[self::METHODS] ?? [];
        if (! is_array($methods)) {
            $methods = [];
        }

        $this->methods = array_values(array_filter($methods, static function ($item): bool {
            return is_array($item)
                && isset($item['c'], $item['m'])
                && is_string($item['c'])
                && is_string($item['m']);
        }));
    }

    public function refactor(Node $node): ?Node
    {
        if ($node instanceof ClassMethod) {
            return $this->refactorClassMethod($node);
        }

        if ($node instanceof MethodCall || $node instanceof NullsafeMethodCall) {
            return $this->refactorInstanceCall($node);
        }

        if ($node instanceof StaticCall) {
            return $this->refactorStaticCall($node);
        }

        return null;
    }

    private function refactorInstanceCall(MethodCall|NullsafeMethodCall $node): ?Node
    {
        $methodName = $this->getName($node->name);
        if ($methodName === null) {
            return null;
        }

        $match = $this->findMatchByMethodName($methodName);
        if ($match === null) {
            return null;
        }

        $fqcn = $match['c'];
        $uniqueEnough = (bool) ($match['u'] ?? false);

        $isTypedMatch = $this->isObjectType($node->var, new ObjectType($fqcn));

        if (! $isTypedMatch && ! $uniqueEnough) {
            return null;
        }

        $stmt = $this->findNearestStatement($node);
        if ($stmt === null) {
            return null;
        }

        if ($this->statementAlreadyHasTodo($stmt)) {
            return null;
        }

        $this->addDocCommentAboveStatement($stmt, $this->buildTodoDoc($match));

        return $node;
    }

    private function refactorStaticCall(StaticCall $node): ?Node
    {
        $methodName = $this->getName($node->name);
        if ($methodName === null) {
            return null;
        }

        $match = $this->findMatchByMethodName($methodName);
        if ($match === null) {
            return null;
        }

        $fqcn = $match['c'];
        $uniqueEnough = (bool) ($match['u'] ?? false);

        $calledOn = $node->class;
        $calledClassName = is_string($calledOn) ? $calledOn : $this->getName($calledOn);

        $isExactClassMatch = $calledClassName !== null && ltrim($calledClassName, '\\') === ltrim($fqcn, '\\');

        if (! $isExactClassMatch && ! $uniqueEnough) {
            return null;
        }

        $stmt = $this->findNearestStatement($node);
        if ($stmt === null) {
            return null;
        }

        if ($this->statementAlreadyHasTodo($stmt)) {
            return null;
        }

        $this->addDocCommentAboveStatement($stmt, $this->buildTodoDoc($match));

        return $node;
    }

    private function refactorClassMethod(ClassMethod $node): ?Node
    {
        $methodName = $this->getName($node->name);
        if ($methodName === null) {
            return null;
        }

        $match = $this->findMatchByMethodName($methodName);
        if ($match === null) {
            return null;
        }

        $classNode = $node->getAttribute(AttributeKey::CLASS_NODE);
        if (! $classNode instanceof Class_) {
            return null;
        }

        $fqcn = $match['c'];
        $uniqueEnough = (bool) ($match['u'] ?? false);

        $isInHierarchy = $this->isClassInHierarchy($classNode, $fqcn);

        if (! $isInHierarchy && ! $uniqueEnough) {
            return null;
        }

        if ($node->stmts === null) {
            return null;
        }

        if ($this->methodBodyAlreadyHasTodo($node)) {
            return null;
        }

        $doc = new Doc($this->buildTodoDoc($match));

        // Put it on the first statement if it exists, otherwise add a no-op statement with a comment is messy.
        // So: attach a comment to the first statement (best-effort).
        if (isset($node->stmts[0])) {
            $firstStmt = $node->stmts[0];

            $existing = $firstStmt->getAttribute(AttributeKey::COMMENTS) ?? [];
            if (! is_array($existing)) {
                $existing = [];
            }

            array_unshift($existing, $doc);
            $firstStmt->setAttribute(AttributeKey::COMMENTS, $existing);

            return $node;
        }

        // Empty body: add an empty statement is not great; just attach to method itself.
        $existing = $node->getAttribute(AttributeKey::COMMENTS) ?? [];
        if (! is_array($existing)) {
            $existing = [];
        }

        array_unshift($existing, $doc);
        $node->setAttribute(AttributeKey::COMMENTS, $existing);

        return $node;
    }

    /**
     * @return array{c: string, m: string, n?: string, u?: bool}|null
     */
    private function findMatchByMethodName(string $methodName): ?array
    {
        foreach ($this->methods as $method) {
            if ($method['m'] === $methodName) {
                return $method;
            }
        }

        return null;
    }

    private function findNearestStatement(Node $node): ?Stmt
    {
        $current = $node;

        while ($current !== null) {
            $parent = $current->getAttribute(AttributeKey::PARENT_NODE);
            if (! $parent instanceof Node) {
                break;
            }

            if ($parent instanceof Stmt) {
                return $parent;
            }

            $current = $parent;
        }

        return null;
    }

    private function statementAlreadyHasTodo(Stmt $stmt): bool
    {
        $comments = $stmt->getAttribute(AttributeKey::COMMENTS) ?? [];
        if (! is_array($comments)) {
            return false;
        }

        foreach ($comments as $comment) {
            $text = method_exists($comment, 'getText') ? (string) $comment->getText() : (string) $comment;
            if (str_contains($text, '@TODO UPGRADE TASK')) {
                return true;
            }
        }

        return false;
    }

    private function methodBodyAlreadyHasTodo(ClassMethod $method): bool
    {
        if ($method->stmts === null) {
            return false;
        }

        if (! isset($method->stmts[0])) {
            $comments = $method->getAttribute(AttributeKey::COMMENTS) ?? [];
            return $this->commentsContainTodo($comments);
        }

        $comments = $method->stmts[0]->getAttribute(AttributeKey::COMMENTS) ?? [];
        return $this->commentsContainTodo($comments);
    }

    /**
     * @param mixed $comments
     */
    private function commentsContainTodo($comments): bool
    {
        if (! is_array($comments)) {
            return false;
        }

        foreach ($comments as $comment) {
            $text = method_exists($comment, 'getText') ? (string) $comment->getText() : (string) $comment;
            if (str_contains($text, '@TODO UPGRADE TASK')) {
                return true;
            }
        }

        return false;
    }

    private function addDocCommentAboveStatement(Stmt $stmt, string $docText): void
    {
        $doc = new Doc($docText);

        $existing = $stmt->getAttribute(AttributeKey::COMMENTS) ?? [];
        if (! is_array($existing)) {
            $existing = [];
        }

        array_unshift($existing, $doc);
        $stmt->setAttribute(AttributeKey::COMMENTS, $existing);
    }

    /**
     * Builds a docblock string; includes your 'n' note when present.
     *
     * @param array{c: string, m: string, n?: string, u?: bool} $match
     */
    private function buildTodoDoc(array $match): string
    {
        $note = isset($match['n']) && is_string($match['n']) && $match['n'] !== '' ? ' ' . $match['n'] : '';

        return '/** @TODO UPGRADE TASK - this method may no longer be available.' . $note . ' */';
    }

    private function isClassInHierarchy(Class_ $classNode, string $targetFqcn): bool
    {
        $className = $classNode->namespacedName?->toString();
        if ($className === null || $className === '') {
            return false;
        }

        $className = ltrim($className, '\\');
        $targetFqcn = ltrim($targetFqcn, '\\');

        if ($className === $targetFqcn) {
            return true;
        }

        if (! $this->reflectionProvider->hasClass($className)) {
            return false;
        }

        $reflection = $this->reflectionProvider->getClass($className);

        return $reflection->isSubclassOf($targetFqcn);
    }
}
