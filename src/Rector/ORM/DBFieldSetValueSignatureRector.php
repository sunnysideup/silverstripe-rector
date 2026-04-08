<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\ORM;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\UnionType;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class DBFieldSetValueSignatureRector extends AbstractRector
{
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Ensures setValue() method on classes extending DBField has the correct PHP 8 signature',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyField extends \SilverStripe\ORM\FieldType\DBField
{
    public function setValue($value, $record = null, $markChanged = true)
    {
        return $this;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class MyField extends \SilverStripe\ORM\FieldType\DBField
{
    public function setValue(mixed $value, null|array|ModelData $record = null, bool $markChanged = true): static
    {
        return $this;
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

        $method = $node->getMethod('setValue');
        if (! $method) {
            return null;
        }

        $hasChanged = false;
        $params = $method->params;
        $needsParamUpdate = false;

        // Check if parameters need updating (count or types)
        if (count($params) !== 3) {
            $needsParamUpdate = true;
        } else {
            // Check Param 0: mixed
            if ($params[0]->type === null || ! $this->isName($params[0]->type, 'mixed')) {
                $needsParamUpdate = true;
            }
            // Check Param 1: null|array|ModelData
            if (! $params[1]->type instanceof UnionType || count($params[1]->type->types) !== 3) {
                $needsParamUpdate = true;
            }
            // Check Param 2: bool
            if ($params[2]->type === null || ! $this->isName($params[2]->type, 'bool')) {
                $needsParamUpdate = true;
            }
        }

        if ($needsParamUpdate) {
            // Param 0: mixed $value
            $var0 = isset($params[0]) ? $params[0]->var : new Variable('value');
            $newParam0 = new Param($var0, null, new Identifier('mixed'));

            // Param 1: null|array|ModelData $record = null
            $var1 = isset($params[1]) ? $params[1]->var : new Variable('record');
            $newParam1 = new Param(
                $var1,
                new ConstFetch(new Name('null')),
                new UnionType([
                    new Identifier('null'),
                    new Identifier('array'),
                    new Name('ModelData'),
                ])
            );

            // Param 2: bool $markChanged = true
            $var2 = isset($params[2]) ? $params[2]->var : new Variable('markChanged');
            $newParam2 = new Param(
                $var2,
                new ConstFetch(new Name('true')),
                new Identifier('bool')
            );

            $method->params = [$newParam0, $newParam1, $newParam2];
            $hasChanged = true;
        }

        // Check and update return type: static
        if ($method->returnType === null || ! $this->isName($method->returnType, 'static')) {
            $method->returnType = new Identifier('static');
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
