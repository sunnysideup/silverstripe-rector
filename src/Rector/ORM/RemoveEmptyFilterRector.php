<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\ORM;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Netwerkstatt\SilverstripeRector\Tests\ORM\RemoveEmptyFilterRector\RemoveEmptyFilterRectorTest
 */
final class RemoveEmptyFilterRector extends AbstractRector
{
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
        if (!$this->isName($node->name, 'filter')) {
            return null;
        }

        // Check if caller is DataList or a class that looks like one (for testing)
        $type = $this->getType($node->var);
        if (!$type->isSuperTypeOf(new ObjectType('SilverStripe\ORM\DataList'))->yes()) {
            // If the type system is failing in tests, we can fall back to checking the method caller's name
            // but for a robust rule, ObjectType is preferred.
            if (!$this->isObjectType($node->var, new ObjectType('SilverStripe\ORM\DataList'))) {
                return null;
            }
        }

        $args = $node->getArgs();
        if (count($args) !== 1) {
            return null;
        }

        $argValue = $args[0]->value;
        if (!$argValue instanceof String_ || $argValue->value !== '') {
            return null;
        }

        return $node->var;
    }
}
