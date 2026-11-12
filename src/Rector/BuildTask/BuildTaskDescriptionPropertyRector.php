<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\BuildTask;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\RuleDefinition;

final class BuildTaskDescriptionPropertyRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Ensures the $description property on BuildTask classes is protected, static, and typed as string.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use SilverStripe\Dev\BuildTask;

class MyTask extends BuildTask
{
    private $description = 'My description';
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
use SilverStripe\Dev\BuildTask;

class MyTask extends BuildTask
{
    protected static string $description = 'My description';
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
        if (! $this->isObjectType($node, new ObjectType(\SilverStripe\Dev\BuildTask::class))) {
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
        // This handles converting non-static to static and fixing visibility simultaneously
        $expectedFlags = Modifiers::PROTECTED | Modifiers::STATIC;
        if ($property->flags !== $expectedFlags) {
            $property->flags = $expectedFlags;
            $hasChanged = true;
        }

        return $hasChanged ? $node : null;
    }
}
