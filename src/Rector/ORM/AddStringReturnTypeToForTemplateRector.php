<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\ORM;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddStringReturnTypeToForTemplateRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Adds string return type to forTemplate() on DBField subclasses',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyField extends DBField
{
    public function forTemplate()
    {
        return 'test';
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class MyField extends DBField
{
    public function forTemplate(): string
    {
        return 'test';
    }
}
CODE_SAMPLE
                )
            ]
        );
    }

    public function getNodeTypes(): array
    {
        // Target the Class_ node to easily check inheritance context
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        // Check if the class is or extends SilverStripe\ORM\FieldType\DBField
        if (! $this->isObjectType($node, new ObjectType('SilverStripe\ORM\FieldType\DBField'))) {
            return null;
        }

        $method = $node->getMethod('forTemplate');

        if (! $method instanceof ClassMethod) {
            return null;
        }

        // If it already has a return type, skip it to prevent infinite loops
        if ($method->returnType !== null) {
            return null;
        }

        // Add the strict "string" return type declaration
        $method->returnType = new Identifier('string');

        return $node;
    }
}
