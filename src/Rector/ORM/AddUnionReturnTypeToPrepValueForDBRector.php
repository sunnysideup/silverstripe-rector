<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\ORM;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\UnionType as NodeUnionType;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddUnionReturnTypeToPrepValueForDBRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Adds array|string|null return type to prepValueForDB() on DBString subclasses',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class EmailAddress extends DBString
{
    public function prepValueForDB(mixed $value): mixed
    {
        return $value;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class EmailAddress extends DBString
{
    public function prepValueForDB(mixed $value): array|string|null
    {
        return $value;
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
        // Ensure the class extends SilverStripe\ORM\FieldType\DBString
        if (! $this->isObjectType($node, new ObjectType('SilverStripe\ORM\FieldType\DBString'))) {
            return null;
        }

        $method = $node->getMethod('prepValueForDB');

        if (! $method instanceof ClassMethod) {
            return null;
        }

        // Prevent infinite loops by checking if it already has the exact union type
        if ($method->returnType instanceof NodeUnionType) {
            $types = array_map(function ($type) {
                return $type->toString();
            }, $method->returnType->types);
            
            sort($types);
            
            if ($types === ['array', 'null', 'string']) {
                return null;
            }
        }

        // Construct the array|string|null union type
        $method->returnType = new NodeUnionType([
            new Identifier('array'),
            new Identifier('string'),
            new Identifier('null')
        ]);

        return $node;
    }
}
