<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\ORM;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class DBFieldAddToQuerySignatureRector extends AbstractRector
{
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Ensures addToQuery() method on classes extending DBField has the signature: public function addToQuery(SQLSelect &$query)',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyField extends \SilverStripe\ORM\FieldType\DBField
{
    protected function addToQuery($query)
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class MyField extends \SilverStripe\ORM\FieldType\DBField
{
    public function addToQuery(\SilverStripe\ORM\Queries\SQLSelect &$query)
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
        // 1. Check if the class extends DBField recursively
        if (! $this->isObjectType($node, new ObjectType('SilverStripe\ORM\FieldType\DBField'))) {
            return null;
        }

        // 2. Look for the addToQuery method
        $method = $node->getMethod('addToQuery');
        if (! $method) {
            return null;
        }

        $hasChanged = false;

        // 3. Ensure the parameter is exactly: SQLSelect &$query
        $needsParamUpdate = false;
        $params = $method->params;

        if (count($params) !== 1) {
            $needsParamUpdate = true;
        } else {
            $param = $params[0];
            // Check if it's passed by reference, named 'query', and type-hinted as 'SQLSelect' (or fully qualified)
            if (! $param->byRef || ! $this->isName($param->var, 'query')) {
                $needsParamUpdate = true;
            } elseif ($param->type === null) {
                $needsParamUpdate = true;
            } elseif (! $this->isName($param->type, 'SilverStripe\ORM\Queries\SQLSelect') && ! $this->isName($param->type, 'SQLSelect')) {
                $needsParamUpdate = true;
            }
        }

        if ($needsParamUpdate) {
            $newParam = new Param(new Variable('query'));
            $newParam->type = new FullyQualified('SilverStripe\ORM\Queries\SQLSelect');
            $newParam->byRef = true; // This adds the & for pass-by-reference
            
            $method->params = [$newParam];
            $hasChanged = true;
        }

        // 4. Ensure the method is strictly public
        if (! $method->isPublic()) {
            $method->flags = ($method->flags & ~Modifiers::PROTECTED & ~Modifiers::PRIVATE) | Modifiers::PUBLIC;
            $hasChanged = true;
        }

        return $hasChanged ? $node : null;
    }
}
