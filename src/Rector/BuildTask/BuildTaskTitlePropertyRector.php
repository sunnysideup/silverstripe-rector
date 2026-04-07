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

final class BuildTaskTitlePropertyRector extends AbstractRector
{
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Ensures the $title property on BuildTask classes is protected and typed as string',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyTask extends \SilverStripe\Dev\BuildTask
{
    private $title = 'My Title';
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
class MyTask extends \SilverStripe\Dev\BuildTask
{
    protected string $title = 'My Title';
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
        // 1. Check if the class extends BuildTask recursively
        if (! $this->isObjectType($node, new ObjectType('SilverStripe\Dev\BuildTask'))) {
            return null;
        }

        // 2. Look for the $title property
        $titleProperty = $node->getProperty('title');
        if (! $titleProperty) {
            return null;
        }

        $hasChanged = false;

        // 3. Ensure the property type is 'string'
        if ($titleProperty->type === null || ! $this->isName($titleProperty->type, 'string')) {
            $titleProperty->type = new Identifier('string');
            $hasChanged = true;
        }

        // 4. Ensure the property is strictly protected
        if (! $titleProperty->isProtected()) {
            $titleProperty->flags = ($titleProperty->flags & ~Modifiers::PUBLIC & ~Modifiers::PRIVATE) | Modifiers::PROTECTED;
            $hasChanged = true;
        }

        return $hasChanged ? $node : null;
    }
}
