<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\ORM;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use Netwerkstatt\SilverstripeRector\Traits\MethodHelper;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Netwerkstatt\SilverstripeRector\Tests\ORM\RemoveEmptyFilterRector\RemoveEmptyFilterRectorTest
 */
final class RemoveEmptyFilterRector extends AbstractRector
{
    use MethodHelper;

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove empty filter() calls from DataList', [
            new CodeSample(
                'SiteTree::get()->filter("");',
                'SiteTree::get();'
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node): ?Node
    {
        $expr = $node->expr;
        if (!$expr instanceof MethodCall) {
            return null;
        }

        if (!$this->isName($expr->name, 'filter')) {
            return null;
        }

        // Use MethodHelper's string-matching logic to identify the class
        // This is more resilient in test environments where reflection might fail
        $callerType = $this->getType($expr->var);
        $className = $this->getName($expr->var) ?? '';
        
        $isDataList = $this->isObjectType($expr->var, new \PHPStan\Type\ObjectType('SilverStripe\ORM\DataList')) || 
                      $this->isClassSameOrSubclassOfConfigured($className, 'SilverStripe\ORM\DataList');

        if (!$isDataList) {
            return null;
        }

        $args = $expr->getArgs();
        if (count($args) !== 1) {
            return null;
        }

        $argValue = $args[0]->value;
        if ($argValue instanceof String_ && $argValue->value === '') {
            // Replace the entire expression's inner call with just the caller
            $node->expr = $expr->var;
            return $node;
        }

        return null;
    }
}
