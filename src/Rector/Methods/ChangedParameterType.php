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
use PhpParser\Node\Param;
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

final class ChangedParameterType extends AbstractRector implements ConfigurableRectorInterface
{
    use MethodHelper;
    
    /**
     * @var array<int, array{c: string, m: string, n: string, u?: bool, p?: int, t?: string}>
     */
    private array $changes = [];

    public function __construct(
        private readonly ReflectionProvider $reflectionProvider
    ) {}

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Adds TODO upgrade comments for method calls/overrides where a parameter type changed, but only if the current argument/parameter does not already match the new type.',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
use SilverStripe\Dev\BuildTask;
use Symfony\Component\Console\Output\OutputInterface;

class MyTask extends BuildTask
{
    // Old signature: public function run($request)
    // New signature: public function run(HTTPRequest $request)
    
    public function run($request)
    {
        // ...
    }
}

// This will get flagged - passing wrong type
$task->run($_GET);

// This will NOT get flagged - already passing correct type  
$task->run($httpRequest);
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
use SilverStripe\Dev\BuildTask;
use Symfony\Component\Console\Output\OutputInterface;

class MyTask extends BuildTask
{
    // Old signature: public function run($request)
    // New signature: public function run(HTTPRequest $request)
    
    // @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Changed parameter type from mixed to HTTPRequest for $request
    public function run($request)
    {
        // ...
    }
}

// @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Changed parameter type from mixed to HTTPRequest for $request
$task->run($_GET);

// This will NOT get flagged - already passing correct type  
$task->run($httpRequest);
CODE_SAMPLE,
                    [
                        [
                            'c' => 'BuildTask',
                            'm' => 'run',
                            'p' => 0,  // First parameter (0-indexed)
                            't' => 'HTTPRequest',  // Expected new type
                            'n' => 'Changed parameter type from mixed to HTTPRequest for $request',
                            'u' => true
                        ]
                    ]
                ),
            ]
        );
    }

    /**
     * Override the configure method to support parameter position and type
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
                'p' => isset($item['p']) ? (int) $item['p'] : null,  // parameter position (0-indexed)
                't' => isset($item['t']) ? (string) $item['t'] : null, // expected type
            ];
        }
    }

    /**
     * Override refactorExpression to check argument types
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

            // If parameter position and type are specified, check if the argument matches
            if ($change['p'] !== null && $change['t'] !== null) {
                if ($this->argumentAlreadyMatchesType($expr, $change['p'], $change['t'])) {
                    // Skip - the argument already matches the new type
                    continue;
                }
            }

            $todoLine = $this->buildTodoLine($change['c'], $change['m'], $change['n']);

            if ($this->appendTodoDocCommentSafely($expression, $todoLine)) {
                $changed = true;
            }
        }

        return $changed ? $expression : null;
    }

    /**
     * Override refactorClassMethod to check parameter type hints
     */
    private function refactorClassMethod(ClassMethod $classMethod, ?bool $withRename = false): ?Node
    {
        if (!$classMethod->name instanceof Identifier) {
            return null;
        }

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

            // If parameter position and type are specified, check if the parameter already has the correct type
            if ($change['p'] !== null && $change['t'] !== null) {
                if ($this->parameterAlreadyHasType($classMethod, $change['p'], $change['t'])) {
                    // Skip - the parameter already has the correct type hint
                    continue;
                }
            }

            $todoLine = $this->buildTodoLine($change['c'], $change['m'], $change['n']);

            if ($this->appendTodoDocCommentSafely($classMethod, $todoLine)) {
                $changed = true;
            }
        }

        return $changed ? $classMethod : null;
    }

    /**
     * Check if the argument at the specified position already matches the expected type
     */
    private function argumentAlreadyMatchesType(
        MethodCall|NullsafeMethodCall|StaticCall $call,
        int $parameterPosition,
        string $expectedType
    ): bool {
        // Check if the argument exists at this position
        if (!isset($call->args[$parameterPosition])) {
            return false;
        }

        $arg = $call->args[$parameterPosition];
        $argType = $this->getType($arg->value);

        // If we can't determine the type, be conservative and flag it
        if ($this->isUnknownType($argType)) {
            return false;
        }

        // Check if the argument type matches the expected type
        return $this->typeMatchesExpected($argType, $expectedType);
    }

    /**
     * Check if the parameter at the specified position already has the correct type hint
     */
    private function parameterAlreadyHasType(
        ClassMethod $classMethod,
        int $parameterPosition,
        string $expectedType
    ): bool {
        // Check if the parameter exists at this position
        if (!isset($classMethod->params[$parameterPosition])) {
            return false;
        }

        $param = $classMethod->params[$parameterPosition];
        
        // Check if the parameter has a type hint
        if ($param->type === null) {
            return false;
        }

        // Get the type hint as a string
        $typeHint = $this->getTypeHintString($param);
        
        if ($typeHint === null) {
            return false;
        }

        // Compare the type hint with the expected type (case-insensitive, handle both short and FQCN)
        return $this->typeNamesMatch($typeHint, $expectedType);
    }

    /**
     * Extract type hint as a string from a parameter
     */
    private function getTypeHintString(Param $param): ?string
    {
        if ($param->type === null) {
            return null;
        }

        if ($param->type instanceof Name) {
            return $param->type->toString();
        }

        if ($param->type instanceof Identifier) {
            return $param->type->toString();
        }

        // Handle union types, nullable types, etc.
        // For now, we'll be conservative and return null for complex types
        return null;
    }

    /**
     * Check if a runtime type matches the expected type
     */
    private function typeMatchesExpected(Type $argType, string $expectedType): bool
    {
        if ($argType instanceof UnionType) {
            // For union types, check if any of the types match
            foreach ($argType->getTypes() as $subType) {
                if ($this->typeMatchesExpected($subType, $expectedType)) {
                    return true;
                }
            }
            return false;
        }

        if ($argType instanceof ObjectType) {
            $className = $argType->getClassName();
            return $this->typeNamesMatch($className, $expectedType);
        }

        // For non-object types, we can't easily compare
        return false;
    }

    /**
     * Check if two type names match (handles both FQCN and short names)
     */
    private function typeNamesMatch(string $actualType, string $expectedType): bool
    {
        $actualType = ltrim($actualType, '\\');
        $expectedType = ltrim($expectedType, '\\');

        // Exact match
        if (strcasecmp($actualType, $expectedType) === 0) {
            return true;
        }

        // Check if actual is a subclass of expected
        if ($this->reflectionProvider->hasClass($actualType) && $this->reflectionProvider->hasClass($expectedType)) {
            $actualReflection = $this->reflectionProvider->getClass($actualType);
            return $actualReflection->isSubclassOf($expectedType);
        }

        // Check short name match
        $actualShort = substr(strrchr('\\' . $actualType, '\\'), 1);
        $expectedShort = substr(strrchr('\\' . $expectedType, '\\'), 1);
        
        if (strcasecmp($actualShort, $expectedShort) === 0) {
            return true;
        }

        return false;
    }
}