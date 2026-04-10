<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\ORM;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class DataObjectGetToClassGetRector extends AbstractRector
{
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Converts DataObject::get(SomeClass::class) to SomeClass::get()',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$steps = \SilverStripe\ORM\DataObject::get(OrderStep::class);
$more = \SilverStripe\ORM\DataObject::get('My\Custom\Class');
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$steps = OrderStep::get();
$more = \My\Custom\Class::get();
CODE_SAMPLE
                )
            ]
        );
    }

    public function getNodeTypes(): array
    {
        // We are targeting static method calls this time!
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        // 1. Ensure the method is being called EXACTLY on DataObject (resolves FQCN if imported)
        if (! $this->isName($node->class, 'SilverStripe\ORM\DataObject')) {
            return null;
        }

        // 2. Ensure the method being called is get()
        if (! $this->isName($node->name, 'get')) {
            return null;
        }

        // 3. Ensure there is at least one argument to extract the class from
        $args = $node->getArgs();
        if (count($args) === 0) {
            return null;
        }

        $firstArgValue = $args[0]->value;
        $targetClassNode = null;

        // 4A. Handle DataObject::get(OrderStep::class)
        if ($firstArgValue instanceof ClassConstFetch && $this->isName($firstArgValue->name, 'class')) {
            $targetClassNode = $firstArgValue->class;
        } 
        // 4B. Handle DataObject::get('App\Models\OrderStep')
        elseif ($firstArgValue instanceof String_) {
            // Strip any leading slashes from the string to avoid double slashes in the AST
            $className = ltrim($firstArgValue->value, '\\');
            $targetClassNode = new FullyQualified($className);
        } else {
            // If the argument is a dynamic variable like $myClass, we can't safely refactor it statically
            return null;
        }

        // 5. Build and return the new static call: TargetClass::get()
        return new StaticCall($targetClassNode, new Identifier('get'));
    }
}
