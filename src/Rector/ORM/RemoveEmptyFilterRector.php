<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\ORM;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
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
        return new RuleDefinition('Remove empty filter() calls following a DataObject::get()', [
            new CodeSample(
                'MyDataObject::get()->filter("");',
                'MyDataObject::get();'
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
        // 1. Must be a call to filter()
        if (!$this->isName($node->name, 'filter')) {
            return null;
        }

        // 2. Must have exactly one argument that is an empty string
        $args = $node->getArgs();
        if (count($args) !== 1) {
            return null;
        }
        $argValue = $args[0]->value;
        if (!$argValue instanceof String_ || $argValue->value !== '') {
            return null;
        }

        // 3. Structural Check: Is the caller MyDataObject::get()?
        $caller = $node->var;
        
        // Handle $list->filter('') where $list is known or inferred
        if ($this->isObjectType($caller, new \PHPStan\Type\ObjectType('SilverStripe\ORM\DataList'))) {
            return $caller;
        }

        // Handle the specific chain: SomeClass::get()->filter('')
        if ($caller instanceof MethodCall && $this->isName($caller->name, 'get')) {
            $staticCaller = $caller->var;
            if ($staticCaller instanceof StaticCall) {
                $className = $this->getName($staticCaller->class);
                if ($className && $this->isClassSameOrSubclassOfConfigured($className, 'SilverStripe\ORM\DataObject')) {
                    return $caller;
                }
            }
        }

        return null;
    }
}
