<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\Injector;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class FactoryCreateMethodSignatureRector extends AbstractRector
{
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Ensures create() method on Injector Factories is public, has correct parameters, and returns ?object',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyFactory implements \SilverStripe\Core\Injector\Factory
{
    protected function create($service, $params)
    {
        return new \stdClass();
    }
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
class MyFactory implements \SilverStripe\Core\Injector\Factory
{
    public function create($service, array $params = []): ?object
    {
        return new \stdClass();
    }
}
CODE_SAMPLE
                )
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        // 1. Check if the class implements the Factory interface (handles parent class implementations automatically)
        if (! $this->isObjectType($node, new ObjectType('SilverStripe\Core\Injector\Factory'))) {
            return null;
        }

        // 2. Look for the create() method
        $createMethod = $node->getMethod('create');
        if (! $createMethod) {
            return null;
        }

        $hasChanged = false;

        // 3. Check and update parameters: ($service, array $params = [])
        $needsParamUpdate = false;
        $params = $createMethod->params;

        if (count($params) !== 2) {
            $needsParamUpdate = true;
        } else {
            // Validate first parameter: $service (no type, no default)
            if (! $this->isName($params[0]->var, 'service') || $params[0]->type !== null || $params[0]->default !== null) {
                $needsParamUpdate = true;
            }

            // Validate second parameter: array $params = []
            if (! $this->isName($params[1]->var, 'params') || ! $this->isName($params[1]->type, 'array') || ! $params[1]->default instanceof Array_ || count($params[1]->default->items) !== 0) {
                $needsParamUpdate = true;
            }
        }

        if ($needsParamUpdate) {
            $createMethod->params = [
                new Param(new Variable('service')),
                new Param(
                    new Variable('params'),
                    new Array_([]),
                    new Identifier('array')
                ),
            ];
            $hasChanged = true;
        }

        // 4. Check and update return type to: ?object
        $isNullableObject = $createMethod->returnType instanceof NullableType
            && $createMethod->returnType->type instanceof Identifier
            && $this->isName($createMethod->returnType->type, 'object');

        if (! $isNullableObject) {
            // NullableType wraps the actual type in PHP-Parser
            $createMethod->returnType = new NullableType(new Identifier('object'));
            $hasChanged = true;
        }

        // 5. Ensure the method is strictly public
        if (! $createMethod->isPublic()) {
            $createMethod->flags = ($createMethod->flags & ~Modifiers::PROTECTED & ~Modifiers::PRIVATE) | Modifiers::PUBLIC;
            $hasChanged = true;
        }

        return $hasChanged ? $node : null;
    }
}
