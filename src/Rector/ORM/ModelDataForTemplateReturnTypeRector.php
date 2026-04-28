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

final class ModelDataForTemplateReturnTypeRector extends AbstractRector
{
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Adds strict string return type to forTemplate() on all ModelData/ViewableData subclasses (including DBField, DataObject, etc.)',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use SilverStripe\ORM\FieldType\DBField;

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
use SilverStripe\ORM\FieldType\DBField;

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
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        // Check both SS6 (ModelData) and SS4/5 (ViewableData) to ensure compatibility 
        // with the test runner's vendor directory and real-world upgrade paths.
        $isModelData = $this->isObjectType($node, new ObjectType('SilverStripe\Model\ModelData'));
        $isViewableData = $this->isObjectType($node, new ObjectType('SilverStripe\View\ViewableData'));

        if (! $isModelData && ! $isViewableData) {
            return null;
        }

        $method = $node->getMethod('forTemplate');

        if (! $method instanceof ClassMethod) {
            return null;
        }

        // If it already has exactly a 'string' return type, skip to prevent infinite loops
        if ($method->returnType !== null && $this->isName($method->returnType, 'string')) {
            return null;
        }

        // Force the strict "string" return type declaration (overriding ?string, union types, or missing types)
        $method->returnType = new Identifier('string');

        return $node;
    }
}
