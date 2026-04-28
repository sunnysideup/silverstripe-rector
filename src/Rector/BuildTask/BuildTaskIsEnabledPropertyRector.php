<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\BuildTask;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\VarLikeIdentifier;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class BuildTaskIsEnabledPropertyRector extends AbstractRector
{
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Renames $enabled to $is_enabled on BuildTask and ensures it is private static bool.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use SilverStripe\Dev\BuildTask;

class MyTask extends BuildTask
{
    private $enabled = false;
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
use SilverStripe\Dev\BuildTask;

class MyTask extends BuildTask
{
    private static bool $is_enabled = false;
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
        if (! $this->isObjectType($node, new ObjectType('SilverStripe\Dev\BuildTask'))) {
            return null;
        }

        $property = $node->getProperty('enabled');
        if (! $property) {
            return null;
        }

        // In PHP-Parser 5 / Rector 2, property names must be VarLikeIdentifier
        $property->props[0]->name = new VarLikeIdentifier('is_enabled');

        // Set type to bool
        $property->type = new Identifier('bool');

        // Force private static visibility
        $property->flags = Modifiers::PRIVATE | Modifiers::STATIC;

        return $node;
    }
}
