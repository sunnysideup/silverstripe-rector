<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\Misc;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RenameFieldListMethodsWithoutArrayParamRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Silverstripe 5.3: Renames ->addFieldsToTab($name, $singleField) ' .
            'to ->addFieldToTab($name, $singleField) safely',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass extends \SilverStripe\ORM\DataObject
{
    public function getCMSFields() {
        $myfield = FormField::create();
        $fields->addFieldsToTab('Root.Main', $myfield);
    }
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
class SomeClass extends \SilverStripe\ORM\DataObject
{
    public function getCMSFields() {
        $myfield = FormField::create();
        $fields->addFieldToTab('Root.Main', $myfield);
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, NullsafeMethodCall::class];
    }

    /**
     * @param MethodCall|NullsafeMethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node->name, 'addFieldsToTab')) {
            return null;
        }

        $args = $node->getArgs();
        if (count($args) < 2) {
            return null;
        }

        $secondArgValue = $args[1]->value;

        // 1. Explicit array node
        if ($secondArgValue instanceof Array_) {
            return null;
        }

        // 2. FieldList objects act as arrays of fields
        if ($this->isObjectType($secondArgValue, new ObjectType('SilverStripe\Forms\FieldList'))) {
            return null;
        }

        // 3. Fast AST fallbacks for guaranteed single objects.
        // This is crucial for isolated tests where PHPStan degrades classes to MixedType.
        if ($secondArgValue instanceof \PhpParser\Node\Expr\New_) {
            $node->name = new Identifier('addFieldToTab');
            return $node;
        }

        if ($secondArgValue instanceof \PhpParser\Node\Expr\StaticCall && $this->isName($secondArgValue->name, 'create')) {
            $node->name = new Identifier('addFieldToTab');
            return $node;
        }

        // 4. Strict Type Inference Fallback
        $type = $this->getType($secondArgValue);
        
        // SAFETY FIRST POLICY:
        // isArray()->no() means "I am mathematically certain this is NOT an array."
        // If it evaluates to an Object, this returns true (we rename).
        // If it evaluates to MixedType (e.g. your complex $heroTabFields loop), this returns false (we skip).
        if ($type->isArray()->no()) {
            $node->name = new Identifier('addFieldToTab');
            return $node;
        }

        // When in doubt, do not modify.
        return null;
    }
}
