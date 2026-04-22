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

final class RenamedTo extends AbstractRector implements ConfigurableRectorInterface
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
            'Adds TODO upgrade comments and attempts to auto-rename method calls/overrides.',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
$extension->MetaTags($params);
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
/** @TODO SSU RECTOR UPGRADE TASK - FluentSiteTreeExtension::MetaTags: renamed to updateMetaTags() FQCN: (TractorCow\Fluent\Extension\FluentSiteTreeExtension) */
$extension->updateMetaTags($params);
CODE_SAMPLE,
                    [['c' => 'TractorCow\Fluent\Extension\FluentSiteTreeExtension', 'm' => 'MetaTags', 'n' => 'renamed to updateMetaTags()', 'u' => false]]
                ),
            ]
        );
    }

// public function configure(array $configuration): void - see Trait

// public function getNodeTypes(): array - see Trait

    /**
     * @param Expression|ClassMethod $node
     */
    // public function refactor(Node $node): ?Node - see Trait

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

            // Attempt auto-fix renaming
            $newName = $this->extractNewMethodName($change['n']);
            if ($newName !== null && $newName !== $methodName) {
                $expr->name = new Identifier($newName);
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

            // Attempt auto-fix renaming
            $newName = $this->extractNewMethodName($change['n']);
            if ($newName !== null && $newName !== $methodName) {
                $classMethod->name = new Identifier($newName);
                $changed = true;
            }
        }

        return $changed ? $classMethod : null;
    }

    private function extractNewMethodName(string $note): ?string
    {
        // Matches "renamed to onInit()" or "renamed to RecordList" safely extracting the method name
        if (preg_match('/renamed to ([a-zA-Z0-9_]+)(?:\(\))?/i', $note, $matches)) {
            return $matches[1];
        }

        return null;
    }

}
