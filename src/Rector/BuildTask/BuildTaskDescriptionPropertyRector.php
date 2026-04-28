<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\BuildTask;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class BuildTaskDescriptionPropertyRector extends AbstractRector
{
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Ensures the $description property on BuildTask classes is protected, static, and typed as string.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyTask extends \SilverStripe\Dev\BuildTask
{
    private $description = 'My Task';
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
class MyTask extends \SilverStripe\Dev\BuildTask
{
    protected static string $description = 'My Task';
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

        $property = $node->getProperty('description');
        if (! $property) {
            return null;
        }

        $hasChanged = false;

        // 1. Force Type to string
        if ($property->type === null || ! $this->isName($property->type, 'string')) {
            $property->type = new Identifier('string');
            $hasChanged = true;
        }

        // 2. Force Flags: protected static
        // We reset flags to specifically PROTECTED | STATIC to clear private/public/readonly/etc.
        $expectedFlags = Modifiers::PROTECTED | Modifiers::STATIC;
        if ($property->flags !== $expectedFlags) {
            $property->flags = $expectedFlags;
            $hasChanged = true;
        }

        return $hasChanged ? $node : null;
    }
}
