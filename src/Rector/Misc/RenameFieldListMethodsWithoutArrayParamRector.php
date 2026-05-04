<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\Misc;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
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
            'Silverstripe 5.3: Renames ->addFieldsToTab() and ->removeFieldsFromTab() to their singular equivalents safely',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass extends \SilverStripe\ORM\DataObject
{
    public function getCMSFields() {
        $myfield = FormField::create();
        $fields->addFieldsToTab('Root.Main', $myfield);
        $fields->removeFieldsFromTab('Root.Main', 'Content');
    }
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
class SomeClass extends \SilverStripe\ORM\DataObject
{
    public function getCMSFields() {
        $myfield = FormField::create();
        $fields->addFieldToTab('Root.Main', $myfield);
        $fields->removeFieldFromTab('Root.Main', 'Content');
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
        $methodName = $this->getName($node->name);
        
        if ($methodName === 'addFieldsToTab') {
            $targetMethod = 'addFieldToTab';
        } elseif ($methodName === 'removeFieldsFromTab') {
            $targetMethod = 'removeFieldFromTab';
        } else {
            return null;
        }

        $args = $node->getArgs();
        if (count($args) < 2) {
            return null;
        }

        $secondArgValue = $args[1]->value;

        // 1. Explicit array node (Skip immediately)
        if ($secondArgValue instanceof Array_) {
            return null;
        }

        // 2. Explicit string node (Guaranteed safe for removeFieldFromTab)
        if ($secondArgValue instanceof String_) {
            $node->name = new Identifier($targetMethod);
            return $node;
        }

        // 3. Do not mutate FieldList objects (act as arrays of fields)
        if ($this->isObjectType($secondArgValue, new ObjectType('SilverStripe\Forms\FieldList'))) {
            return null;
        }

        // 4. Fast AST fallbacks for guaranteed single objects.
        if ($secondArgValue instanceof \PhpParser\Node\Expr\New_) {
            $node->name = new Identifier($targetMethod);
            return $node;
        }

        if ($secondArgValue instanceof \PhpParser\Node\Expr\StaticCall && $this->isName($secondArgValue->name, 'create')) {
            $node->name = new Identifier($targetMethod);
            return $node;
        }

        // 5. Strict Type Inference Fallback
        $type = $this->getType($secondArgValue);
        
        // If it evaluates to a string explicitly (e.g. from variable), rename.
        if ($type->isString()->yes()) {
            $node->name = new Identifier($targetMethod);
            return $node;
        }

        // SAFETY FIRST POLICY:
        // isArray()->no() returns TRUE if it is mathematically certain this is NOT an array.
        // MixedType degrades to false, keeping complex loops (like your $heroTabFields) safe.
        if ($type->isArray()->no()) {
            $node->name = new Identifier($targetMethod);
            return $node;
        }

        return null;
    }
}
