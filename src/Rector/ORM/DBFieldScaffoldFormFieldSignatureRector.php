<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\ORM;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class DBFieldScaffoldFormFieldSignatureRector extends AbstractRector
{
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Ensures scaffoldFormField() method on classes extending DBField has the correct signature',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyField extends \SilverStripe\ORM\FieldType\DBField
{
    public function scaffoldFormField($title = null, $params = null)
    {
        return null;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class MyField extends \SilverStripe\ORM\FieldType\DBField
{
    public function scaffoldFormField(?string $title = null, array $params = []): ?\SilverStripe\Forms\FormField
    {
        return null;
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
        if (! $this->isObjectType($node, new ObjectType('SilverStripe\ORM\FieldType\DBField'))) {
            return null;
        }

        $method = $node->getMethod('scaffoldFormField');
        if (! $method) {
            return null;
        }

        $hasChanged = false;
        $params = $method->params;

        // Verify Param 0: ?string $title = null
        $validParam0 = isset($params[0]) 
            && $params[0]->type instanceof NullableType 
            && $this->isName($params[0]->type->type, 'string')
            && $params[0]->default instanceof ConstFetch
            && $this->isName($params[0]->default->name, 'null');

        // Verify Param 1: array $params = []
        $validParam1 = isset($params[1])
            && $params[1]->type instanceof Identifier
            && $this->isName($params[1]->type, 'array')
            && $params[1]->default instanceof Array_
            && count($params[1]->default->items) === 0;

        if (! $validParam0 || ! $validParam1 || count($params) !== 2) {
            $var0 = isset($params[0]) ? $params[0]->var : new Variable('title');
            $newParam0 = new Param(
                $var0,
                new ConstFetch(new Name('null')),
                new NullableType(new Identifier('string'))
            );

            $var1 = isset($params[1]) ? $params[1]->var : new Variable('params');
            $newParam1 = new Param(
                $var1,
                new Array_([]),
                new Identifier('array')
            );

            $method->params = [$newParam0, $newParam1];
            $hasChanged = true;
        }

        // Verify Return Type: ?\SilverStripe\Forms\FormField
        $validReturn = $method->returnType instanceof NullableType
            && $method->returnType->type instanceof Name
            && $this->isName($method->returnType->type, 'SilverStripe\Forms\FormField');

        if (! $validReturn) {
            $method->returnType = new NullableType(new FullyQualified('SilverStripe\Forms\FormField'));
            $hasChanged = true;
        }

        // Ensure visibility is public
        if (! $method->isPublic()) {
            $method->flags = ($method->flags & ~Modifiers::PROTECTED & ~Modifiers::PRIVATE) | Modifiers::PUBLIC;
            $hasChanged = true;
        }

        return $hasChanged ? $node : null;
    }
}
