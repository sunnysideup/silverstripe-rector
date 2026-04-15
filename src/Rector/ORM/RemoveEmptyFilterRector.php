<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\ORM;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
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
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        // Use MethodHelper to verify this is a DataList->filter() call
        if (!$this->isClassSameOrSubclassOfConfigured($this->getName($node->var) ?? '', 'SilverStripe\ORM\DataList')) {
             // Fallback to type check if name resolution is insufficient in tests
             if (!$this->isObjectType($node->var, new \PHPStan\Type\ObjectType('SilverStripe\ORM\DataList'))) {
                return null;
             }
        }

        if (!$this->isName($node->name, 'filter')) {
            return null;
        }

        $args = $node->getArgs();
        if (count($args) !== 1) {
            return null;
        }

        $firstArgValue = $args[0]->value;
        if (!$firstArgValue instanceof String_ || $firstArgValue->value !== '') {
            return null;
        }

        return $node->var;
    }
}
