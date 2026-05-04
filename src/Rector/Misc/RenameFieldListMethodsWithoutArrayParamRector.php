<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\Misc;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\MixedType;
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

        // 1. AST Checks for Explicit Types
        if ($secondArgValue instanceof Array_) {
            return null;
        }

        if ($secondArgValue instanceof String_) {
            $node->name = new Identifier($targetMethod);
            return $node;
        }

        if ($this->isObjectType($secondArgValue, new ObjectType('SilverStripe\Forms\FieldList'))) {
            return null;
        }

        if ($secondArgValue instanceof \PhpParser\Node\Expr\New_ || 
           ($secondArgValue instanceof \PhpParser\Node\Expr\StaticCall && $this->isName($secondArgValue->name, 'create'))
        ) {
            $node->name = new Identifier($targetMethod);
            return $node;
        }

        // 2. Strict Type Inference
        $type = $this->getType($secondArgValue);
        
        if ($type->isString()->yes()) {
            $node->name = new Identifier($targetMethod);
            return $node;
        }

        if ($type->isArray()->yes() || $type->isIterable()->yes()) {
            return null;
        }

        // If mathematically certain it is not an array/iterable (e.g., guaranteed Object)
        if ($type->isArray()->no() && ! $type instanceof MixedType) {
            $node->name = new Identifier($targetMethod);
            return $node;
        }

        // 3. Heuristic Fallback for MixedType (Unresolved Variables)
        // In Silverstripe, variable names strongly indicate types when static analysis fails.
        if ($type instanceof MixedType) {
            $nameToCheck = null;
            if ($secondArgValue instanceof Variable && is_string($secondArgValue->name)) {
                $nameToCheck = $secondArgValue->name;
            } elseif ($secondArgValue instanceof PropertyFetch && $secondArgValue->name instanceof Identifier) {
                $nameToCheck = $secondArgValue->name->toString();
            }

            if ($nameToCheck !== null) {
                // Plural -> Arrays -> Skip
                if (preg_match('/fields$/i', $nameToCheck)) {
                    return null;
                }
                // Singular -> Object -> Rename
                if (preg_match('/field$/i', $nameToCheck)) {
                    $node->name = new Identifier($targetMethod);
                    return $node;
                }
            }
        }

        return null;
    }
}
