<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\ORM;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class DBVarcharURLReturnTypeRector extends AbstractRector
{
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Changes return type of URL() methods to ?string for classes extending DBVarchar',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyVarchar extends \SilverStripe\ORM\FieldType\DBVarchar
{
    public function URL()
    {
        return 'https://example.com';
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class MyVarchar extends \SilverStripe\ORM\FieldType\DBVarchar
{
    public function URL(): ?string
    {
        return 'https://example.com';
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
        // 1. Check if the class extends DBVarchar
        if (! $this->isObjectType($node, new ObjectType('SilverStripe\ORM\FieldType\DBVarchar'))) {
            return null;
        }

        // 2. Look for the URL() method (PhpParser's getMethod is case-insensitive, which matches PHP behavior)
        $urlMethod = $node->getMethod('URL');
        if (! $urlMethod) {
            return null;
        }

        // 3. Check if it already has the ?string return type
        $isNullableString = $urlMethod->returnType instanceof NullableType 
            && $urlMethod->returnType->type instanceof Identifier 
            && $this->isName($urlMethod->returnType->type, 'string');

        if (! $isNullableString) {
            // NullableType wraps the base type to add the `?` prefix
            $urlMethod->returnType = new NullableType(new Identifier('string'));
            return $node;
        }

        return null;
    }
}
