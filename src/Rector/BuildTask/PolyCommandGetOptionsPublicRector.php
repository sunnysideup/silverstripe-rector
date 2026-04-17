<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\BuildTask;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class PolyCommandGetOptionsPublicRector extends AbstractRector
{
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Changes getOptions() method visibility to public for classes extending SilverStripe\PolyExecution\PolyCommand. 
            See https://docs.silverstripe.org/en/6/developer_guides/cli/polycommand/#buildtask',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyCommand extends \SilverStripe\PolyExecution\PolyCommand
{
    protected function getOptions()
    {
        return [];
    }
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
class MyCommand extends \SilverStripe\PolyExecution\PolyCommand
{
    public function getOptions()
    {
        return [];
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
        // 1. Check if the class extends PolyCommand
        if (! $this->isObjectType($node, new ObjectType('SilverStripe\PolyExecution\PolyCommand'))) {
            return null;
        }

        // 2. Check if it has a method named 'getOptions'
        $getOptionsMethod = $node->getMethod('getOptions');
        if (! $getOptionsMethod) {
            return null;
        }

        // 3. Ensure the method is public (using PHP-Parser 5 Modifiers for Rector 2.x)
        if (! $getOptionsMethod->isPublic()) {
            $getOptionsMethod->flags = ($getOptionsMethod->flags & ~Modifiers::PROTECTED & ~Modifiers::PRIVATE) | Modifiers::PUBLIC;

            return $node;
        }

        return null;
    }
}
