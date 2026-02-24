<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\Methods;


use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ObsoleteMethod extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var array<int, array{c: string, m: string, n: string, u: bool}>
     */
    private array $rules = [];

    public function __construct(
        private readonly ReflectionProvider $reflectionProvider
    ) {}

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Adds TODO upgrade comments for calls/overrides of removed methods on known classes'
        );
    }

    /**
     * @param array<int, array{c: string, m: string, n: string, u?: bool}> $configuration
     */
    public function configure(array $configuration): void
    {
        $normalisedRules = [];

        foreach ($configuration as $item) {
            if (! isset($item['c'], $item['m'], $item['n'])) {
                continue;
            }

            $normalisedRules[] = [
                'c' => (string) $item['c'],
                'm' => (string) $item['m'],
                'n' => (string) $item['n'],
                'u' => (bool) ($item['u'] ?? false),
            ];
        }

        $this->rules = $normalisedRules;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [
            MethodCall::class,
            NullsafeMethodCall::class,
            ClassMethod::class,
        ];
    }

    public function refactor(Node $node): Node|null
    {
        if ($node instanceof MethodCall || $node instanceof NullsafeMethodCall) {
            return $this->refactorMethodLikeCall($node);
        }

        if ($node instanceof ClassMethod) {
            return $this->refactorClassMethod($node);
        }

        return null;
    }

    private function refactorMethodLikeCall(MethodCall|NullsafeMethodCall $node): Node|null
    {
        $methodName = $this->getCalledMethodName($node);
        if ($methodName === null) {
            return null;
        }

        foreach ($this->rules as $rule) {
            if ($rule['m'] !== $methodName) {
                continue;
            }

            $matchesKnownType = $this->isObjectType($node->var, new ObjectType($rule['c']));
            $typeCouldBeResolved = $this->canResolveObjectType($node->var);

            if (! $matchesKnownType) {
                if ($typeCouldBeResolved) {
                    continue;
                }

                if ($rule['u'] !== true) {
                    continue;
                }
            }

            $todoLine = $this->buildTodoLine($rule);

            $commentTarget = $this->resolveCommentTargetForCall($node);

            if ($this->hasTodoAlready($commentTarget, $todoLine)) {
                continue;
            }

            $this->appendDocComment($commentTarget, $todoLine);

            return $node;
        }

        return null;
    }

    private function refactorClassMethod(ClassMethod $node): Node|null
    {
        if (! $node->name instanceof Identifier) {
            return null;
        }

        $methodName = $node->name->toString();

        foreach ($this->rules as $rule) {
            if ($rule['m'] !== $methodName) {
                continue;
            }

            if (! $this->isOverridingMethodOnConfiguredClass($node, $rule['c'], $rule['m'])) {
                continue;
            }

            $todoLine = $this->buildTodoLine($rule);

            if ($this->hasTodoAlready($node, $todoLine)) {
                continue;
            }

            $this->appendDocComment($node, $todoLine);

            return $node;
        }

        return null;
    }

    private function getCalledMethodName(MethodCall|NullsafeMethodCall $node): string|null
    {
        if (! $node->name instanceof Identifier) {
            return null;
        }

        return $node->name->toString();
    }

    private function canResolveObjectType(Node $expr): bool
    {
        $type = $this->getType($expr);

        // Unknown / mixed / impossible => treat as unresolved
        if ($type === null) {
            return false;
        }

        $typeClass = $type::class;

        if (
            str_contains($typeClass, 'MixedType') ||
            str_contains($typeClass, 'ErrorType') ||
            str_contains($typeClass, 'NeverType')
        ) {
            return false;
        }

        return true;
    }

    private function resolveCommentTargetForCall(MethodCall|NullsafeMethodCall $node): Node
    {
        $parent = $node->getAttribute(AttributeKey::PARENT_NODE);

        if ($parent instanceof Expression) {
            return $parent;
        }

        return $node;
    }

    private function isOverridingMethodOnConfiguredClass(ClassMethod $classMethod, string $configuredClass, string $methodName): bool
    {
        $classLike = $classMethod->getAttribute(AttributeKey::CLASS_NODE);
        if (! $classLike instanceof Node\Stmt\Class_) {
            return false;
        }

        $currentClassName = $this->getName($classLike);
        if ($currentClassName === null) {
            return false;
        }

        // If the method still exists on the current class itself, this may be the original class;
        // request says the method has already been removed from original class, and we care about overrides/extensions.
        // We still allow inheritance-based detection below.
        if (! $this->reflectionProvider->hasClass($currentClassName)) {
            return false;
        }

        $currentClassReflection = $this->reflectionProvider->getClass($currentClassName);

        // Exact same class only makes sense if source still contains legacy method; usually not desired.
        // We mainly want subclasses/extensions.
        if (! $currentClassReflection->isSubclassOf($configuredClass)) {
            return false;
        }

        // Best-effort: ensure configured parent class is known and the method existed/was expected there.
        if ($this->reflectionProvider->hasClass($configuredClass)) {
            $configuredReflection = $this->reflectionProvider->getClass($configuredClass);

            // If reflection knows nothing about that method (because removed in installed version),
            // we still proceed based on config, which is the source of truth.
            unset($configuredReflection);
        }

        return $classMethod->name->toString() === $methodName;
    }

    /**
     * @param array{c: string, m: string, n: string, u: bool} $rule
     */
    private function buildTodoLine(array $rule): string
    {
        return '@TODO UPGRADE TASK - ' . $rule['c'] . '::' . $rule['m'] . ': ' . $rule['n'];
    }

    private function hasTodoAlready(Node $node, string $todoLine): bool
    {
        $docComment = $node->getDocComment();
        if ($docComment instanceof Doc && str_contains($docComment->getText(), $todoLine)) {
            return true;
        }

        $comments = $node->getComments();
        foreach ($comments as $comment) {
            if (str_contains($comment->getText(), $todoLine)) {
                return true;
            }
        }

        return false;
    }

    private function appendDocComment(Node $node, string $todoLine): void
    {
        $existingDoc = $node->getDocComment();

        if ($existingDoc instanceof Doc) {
            $newDocText = $this->appendTodoToExistingDocblock($existingDoc->getText(), $todoLine);
            $node->setDocComment(new Doc($newDocText));

            return;
        }

        $docText = '/** ' . $todoLine . ' */';
        $node->setDocComment(new Doc($docText));
    }

    private function appendTodoToExistingDocblock(string $docText, string $todoLine): string
    {
        if (str_contains($docText, $todoLine)) {
            return $docText;
        }

        $trimmed = rtrim($docText);

        // Standard multi-line docblock
        if (str_ends_with($trimmed, '*/')) {
            $trimmed = substr($trimmed, 0, -2);
            $trimmed = rtrim($trimmed);

            if (! str_ends_with($trimmed, "\n")) {
                $trimmed .= "\n";
            }

            $trimmed .= ' * ' . $todoLine . "\n";
            $trimmed .= ' */';

            return $trimmed;
        }

        // Fallback (should not normally happen)
        return "/**\n * " . $todoLine . "\n */";
    }
}
