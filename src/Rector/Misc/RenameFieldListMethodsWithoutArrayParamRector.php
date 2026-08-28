<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\Misc;

use Netwerkstatt\SilverstripeRector\Traits\MethodHelper;
use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RenameFieldListMethodsWithoutArrayParamRector extends AbstractRector implements DocumentedRuleInterface
{
    use MethodHelper;

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Silverstripe 5.3: Safely renames ->addFieldsToTab() and ->removeFieldsFromTab() to their singular equivalents, or adds a TODO comment for unknown variables.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$fields->addFieldsToTab('Root.Main', $unknownVariable);
$fields->removeFieldsFromTab('Root.Main', 'Content');
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
// @TODO SSU RECTOR UPGRADE TASK: FieldList::addFieldsToTab() - Mixed type detected. If this is a single field/string, change to addFieldToTab() manually.
$fields->addFieldsToTab('Root.Main', $unknownVariable);
$fields->removeFieldFromTab('Root.Main', 'Content');
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

        // 1. Explicit AST Ignores (Arrays and Collections)[cite: 1]
        if ($secondArgValue instanceof Array_) {
            return null; 
        }

        if ($secondArgValue instanceof \PhpParser\Node\Expr\New_ && $secondArgValue->class instanceof Name) {
            if ($this->isName($secondArgValue->class, 'FieldList')) {
                return null;
            }
        }
        
        if ($secondArgValue instanceof \PhpParser\Node\Expr\StaticCall && $secondArgValue->class instanceof Name) {
            if ($this->isName($secondArgValue->class, 'FieldList')) {
                return null;
            }
        }

        if ($this->isObjectType($secondArgValue, new ObjectType('SilverStripe\Forms\FieldList'))) {
            return null; 
        }

        // 2. Strict Type Inference for Arrays/Iterables[cite: 1]
        $type = $this->getType($secondArgValue);
        if ($type->isArray()->yes() || $type->isIterable()->yes()) {
            return null;
        }

        // 3. Explicit AST/Type Renames (Safe Single Entities)[cite: 1]
        $shouldRename = false;
        if ($secondArgValue instanceof String_) {
            $shouldRename = true;
        } elseif (
            $secondArgValue instanceof \PhpParser\Node\Expr\New_ ||
            ($secondArgValue instanceof \PhpParser\Node\Expr\StaticCall && $this->isName($secondArgValue->name, 'create'))
        ) {
            $shouldRename = true;
        } elseif ($type->isString()->yes() || $type->isObject()->yes()) {
            $shouldRename = true;
        }

        if ($shouldRename) {
            $node->name = new Identifier($targetMethod);
            return $node;
        }

        // 4. Naming Heuristics for MixedType (Unresolved Variables)
        $nameToCheck = null;
        if ($secondArgValue instanceof Variable && is_string($secondArgValue->name)) {
            $nameToCheck = $secondArgValue->name;
        } elseif ($secondArgValue instanceof PropertyFetch && $secondArgValue->name instanceof Identifier) {
            $nameToCheck = $secondArgValue->name->toString();
        }

        if ($nameToCheck !== null) {
            if (preg_match('/fields$/i', $nameToCheck)) {
                return null; // Assumed Array/FieldList -> Ignore
            }
            if (preg_match('/field$/i', $nameToCheck)) {
                $node->name = new Identifier($targetMethod);
                return $node; // Assumed Object -> Rename
            }
        }

        // 5. MIXED/UNKNOWN TYPE: Inject safe TODO note[cite: 1]
        $todoLine = $this->buildTodoLine(
            'FieldList', 
            $methodName, 
            "Mixed type detected. If this is a single field/string, change to {$targetMethod}() manually."
        );
        $this->appendTodoDocCommentSafely($node, $todoLine);

        return $node;
    }
}
