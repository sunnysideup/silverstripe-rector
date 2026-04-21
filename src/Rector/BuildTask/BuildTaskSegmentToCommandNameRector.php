<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\BuildTask;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\VarLikeIdentifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Modifiers;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Netwerkstatt\SilverstripeRector\Tests\BuildTask\BuildTaskSegmentToCommandNameRector\BuildTaskSegmentToCommandNameRectorTest
 */
final class BuildTaskSegmentToCommandNameRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Changes BuildTask $segment to $commandName with protected static string signature', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class MyTask extends \SilverStripe\Dev\BuildTask {
    private static $segment = 'my-task';
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class MyTask extends \SilverStripe\Dev\BuildTask {
    protected static string $commandName = 'my-task';
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isObjectType($node, new ObjectType('SilverStripe\Dev\BuildTask'))) {
            return null;
        }

        $hasChanged = false;

        foreach ($node->getProperties() as $property) {
            foreach ($property->props as $propProperty) {
                if (!$this->isName($propProperty->name, 'segment')) {
                    continue;
                }

                // 1. Rename variable to $commandName
                $propProperty->name = new VarLikeIdentifier('commandName');

                // 2. Enforce protected static visibility
                $property->flags = Modifiers::PROTECTED | Modifiers::STATIC;

                // 3. Enforce string type
                $property->type = new Identifier('string');

                $hasChanged = true;
            }
        }

        return $hasChanged ? $node : null;
    }
}
