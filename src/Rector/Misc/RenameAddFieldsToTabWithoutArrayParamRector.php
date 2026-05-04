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

        // 3. Fast AST fallbacks for guaranteed single objects (solves inline MixedType issues)
        if ($secondArgValue instanceof \PhpParser\Node\Expr\New_) {
            $node->name = new Identifier('addFieldToTab');
            return $node;
        }

        if ($secondArgValue instanceof \PhpParser\Node\Expr\StaticCall && $this->isName($secondArgValue->name, 'create')) {
            $node->name = new Identifier('addFieldToTab');
            return $node;
        }

        // 4. Deep type check via PHPStan for variables
        $type = $this->getType($secondArgValue);
        
        // If it is definitively an array or iterable, SKIP.
        if ($type->isArray()->yes() || $type->isIterable()->yes()) {
            return null;
        }

        // If it is definitively NOT an array (e.g. an ObjectType), RENAME.
        if ($type->isArray()->no()) {
            $node->name = new Identifier('addFieldToTab');
            return $node;
        }

        // 5. Fallback for MixedType/unknown variables:
        // We skip to prevent false positives on complex conditional arrays like $heroTabFields.
        return null;
    }
}
