<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\ORM;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class DBCompositeWriteToManipulationSignatureRector extends AbstractRector
{
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Ensures writeToManipulation() method on classes extending DBComposite has the signature: writeToManipulation(array &$manipulation): void',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyComposite extends \SilverStripe\ORM\FieldType\DBComposite
{
    public function writeToManipulation($manipulation)
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class MyComposite extends \SilverStripe\ORM\FieldType\DBComposite
{
    public function writeToManipulation(array &$manipulation): void
    {
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
        // 1. Check if the class extends DBComposite recursively
        if (! $this->isObjectType($node, new ObjectType('SilverStripe\ORM\FieldType\DBComposite'))) {
            return null;
        }

        // 2. Look for the writeToManipulation method
        $method = $node->getMethod('writeToManipulation');
        if (! $method) {
            return null;
        }

        $hasChanged = false;

        // 3. Ensure the parameter is exactly: array &$manipulation
        $needsParamUpdate = false;
        $params = $method->params;

        if (count($params) !== 1) {
            $needsParamUpdate = true;
        } else {
            $param = $params[0];
            // Check if it's passed by reference, named 'manipulation', and type-hinted as 'array'
            if (! $param->byRef || ! $this->isName($param->var, 'manipulation') || ! $this->isName($param->type, 'array')) {
                $needsParamUpdate = true;
            }
        }

        if ($needsParamUpdate) {
            $newParam = new Param(new Variable('manipulation'));
            $newParam->type = new Identifier('array');
            $newParam->byRef = true; // This adds the & for pass-by-reference
            
            $method->params = [$newParam];
            $hasChanged = true;
        }

        // 4. Ensure return type is exactly 'void'
        if ($method->returnType === null || ! $this->isName($method->returnType, 'void')) {
            $method->returnType = new Identifier('void');
            $hasChanged = true;
        }

        // 5. Ensure the method is public
        if (! $method->isPublic()) {
            $method->flags = ($method->flags & ~Modifiers::PROTECTED & ~Modifiers::PRIVATE) | Modifiers::PUBLIC;
            $hasChanged = true;
        }

        return $hasChanged ? $node : null;
    }
}
