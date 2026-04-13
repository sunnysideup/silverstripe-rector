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

final class AddVoidReturnTypeToSaveIntoRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Adds void return type to saveInto() on DBField subclasses',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyField extends DBField
{
    public function saveInto($dataObject)
    {
        // save logic
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class MyField extends DBField
{
    public function saveInto($dataObject): void
    {
        // save logic
    }
}
CODE_SAMPLE
                )
            ]
        );
    }

    public function getNodeTypes(): array
    {
        // Target the Class_ node to leverage safe inheritance checking
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        // Ensure the class extends SilverStripe\ORM\FieldType\DBField
        if (! $this->isObjectType($node, new ObjectType('SilverStripe\ORM\FieldType\DBField'))) {
            return null;
        }

        $method = $node->getMethod('saveInto');

        if (! $method instanceof ClassMethod) {
            return null;
        }

        // Skip if it already has a return type to prevent infinite loop
        if ($method->returnType !== null) {
            return null;
        }

        // Assign the strict 'void' return type
        $method->returnType = new Identifier('void');

        return $node;
    }
}
