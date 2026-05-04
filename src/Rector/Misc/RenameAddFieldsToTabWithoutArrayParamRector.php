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

/**
 * @deprecated Use RenameFieldListMethodsWithoutArrayParamRector instead. Will be removed in 2.0.0
 */
final class RenameAddFieldsToTabWithoutArrayParamRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Silverstripe 5.3: DEPRECATED: Use RenameFieldListMethodsWithoutArrayParamRector instead. ' .
            'Will be removed in 2.0.0. Renames ->addFieldsToTab($name, $singleField) ' .
            'to ->addFieldToTab($name, $singleField)',
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
        // Only handle addFieldsToTab(...)
        if (! $this->isName($node->name, 'addFieldsToTab')) {
            return null;
        }

        $args = $node->getArgs();
        if (count($args) < 2) {
            return null;
        }

        $secondArgValue = $args[1]->value;

        // 1. Fast check for explicit Array node
        if ($secondArgValue instanceof Array_) {
            return null;
        }

        // 2. Do not mutate FieldList objects (they act as arrays of fields)
        if ($this->isObjectType($secondArgValue, new ObjectType('SilverStripe\Forms\FieldList'))) {
            return null;
        }

        // 3. Deep conservative type check via PHPStan
        $type = $this->getType($secondArgValue);
        
        // isArray()->no() returns TRUE only if the type is definitively NOT an array.
        // If it is an array, or if PHPStan evaluates it to MixedType because of complex conditional
        // loops, it will evaluate to false. By returning null here, we skip unknown variables 
        // entirely, preventing destructive false-positive refactors.
        if (! $type->isArray()->no()) {
            return null;
        }

        // Safely change method name to addFieldToTab
        $node->name = new Identifier('addFieldToTab');
        return $node;
    }
}
