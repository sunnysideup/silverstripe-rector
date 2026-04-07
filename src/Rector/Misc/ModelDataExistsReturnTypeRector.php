<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\Misc;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ModelDataExistsReturnTypeRector extends AbstractRector
{
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Ensures that any exists() method on classes extending ModelData is public and returns bool',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyModel extends ModelData
{
    protected function exists()
    {
        return true;
    }
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
class MyModel extends ModelData
{
    public function exists(): bool
    {
        return true;
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
        // 1. Check inheritance recursively
        if (! $this->isObjectType($node, new ObjectType('SilverStripe\Model\ModelData'))) {
            return null;
        }

        // 2. Look for the exists() method
        $existsMethod = $node->getMethod('exists');
        if (! $existsMethod) {
            return null;
        }

        $hasChanged = false;

        // 3. Ensure the return type is exactly 'bool'
        // This will successfully catch 'public function exists()' and upgrade it to ': bool'
        if ($existsMethod->returnType === null || ! $this->isName($existsMethod->returnType, 'bool')) {
            $existsMethod->returnType = new Identifier('bool');
            $hasChanged = true;
        }

        // 4. Rector 2.x / PHP-Parser 5: Use the Modifiers class
        if (! $existsMethod->isPublic()) {
            $existsMethod->flags = ($existsMethod->flags & ~Modifiers::PROTECTED & ~Modifiers::PRIVATE) | Modifiers::PUBLIC;
            $hasChanged = true;
        }

        return $hasChanged ? $node : null;
    }
}
