<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\ORM;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
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
        return new RuleDefinition('Remove empty filter() calls from DataObject::get() chains', [
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
        // 1. Identify the 'filter' method
        if (!$this->isName($node->name, 'filter')) {
            return null;
        }

        // 2. Check for empty string argument
        $args = $node->getArgs();
        if (count($args) !== 1) {
            return null;
        }

        $argValue = $args[0]->value;
        if (!$argValue instanceof String_ || $argValue->value !== '') {
            return null;
        }

        // 3. Structural Check (Resilient to reflection failures in tests)
        // Match: AnyClass::get()->filter('')
        if ($node->var instanceof StaticCall && $this->isName($node->var->name, 'get')) {
            return $node->var;
        }

        // Match: AnyClass::get()->other()->filter('')
        if ($node->var instanceof MethodCall) {
            // We can return the caller and let Rector recursively process the chain
            // If the caller is a DataList-returning method, this is safe.
            // Since we're struggling with types, we check if the root of the chain is a ::get()
            $root = $this->findRootStaticCall($node->var);
            if ($root !== null && $this->isName($root->name, 'get')) {
                return $node->var;
            }
        }

        return null;
    }

    private function findRootStaticCall(Node $node): ?StaticCall
    {
        if ($node instanceof StaticCall) {
            return $node;
        }
        if ($node instanceof MethodCall) {
            return $this->findRootStaticCall($node->var);
        }
        return null;
    }
}
