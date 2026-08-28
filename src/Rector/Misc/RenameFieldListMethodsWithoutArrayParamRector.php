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
use PhpParser\Node\Stmt\Expression;
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
            'Silverstripe 5.3: Safely renames ->addFieldsToTab() and ->removeFieldsFromTab() to singular equivalents, or adds a TODO for unknown variables.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$fields->addFieldsToTab('Root.Main', $unknownVariable);
$fields->removeFieldsFromTab('Root.Main', 'Content');
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
// @TODO SSU RECTOR UPGRADE TASK - FieldList::addFieldsToTab: Mixed type detected. If this is a single field/string, change to addFieldToTab() manually.
$fields->addFieldsToTab('Root.Main', $unknownVariable);
$fields->removeFieldFromTab('Root.Main', 'Content');
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * Target Expression to ensure comments render correctly on the statement line.
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node): ?Node
    {
        $methodCall = $node->expr;
        
        if (! $methodCall instanceof MethodCall && ! $methodCall instanceof NullsafeMethodCall) {
            return null;
        }

        $methodName = $this->getName($methodCall->name);
        
        if ($methodName === 'addFieldsToTab') {
            $targetMethod = 'addFieldToTab';
        } elseif ($methodName === 'removeFieldsFromTab') {
            $targetMethod = 'removeFieldFromTab';
        } else {
            return null;
        }

        $args = $methodCall->getArgs();
        if (count($args) < 2) {
            return null;
        }

        $secondArgValue = $args[1]->value;

        // 1. Explicit Arrays -> Ignore immediately
        if ($secondArgValue instanceof Array_) {
            return null; 
        }

        // 2. Explicit FieldList (AST string checks for tests without full class reflection)
        $className = null;
        if ($secondArgValue instanceof \PhpParser\Node\Expr\New_ && $secondArgValue->class instanceof Name) {
            $className = $secondArgValue->class->toString();
        } elseif ($secondArgValue instanceof \PhpParser\Node\Expr\StaticCall && $secondArgValue->class instanceof Name) {
            $className = $secondArgValue->class->toString();
        }

        if ($className !== null && str_ends_with($className, 'FieldList')) {
            return null;
        }

        if ($this->isObjectType($secondArgValue, new ObjectType('SilverStripe\Forms\FieldList'))) {
            return null; 
        }

        // 3. Strict Type Inference
        $type = $this->getType($secondArgValue);
        if ($type->isArray()->yes() || $type->isIterable()->yes()) {
            return null;
        }

        // 4. Safe Single Entity Resolution
        $shouldRename = false;
        
        if ($secondArgValue instanceof String_) {
            $shouldRename = true;
        } elseif (
            $secondArgValue instanceof \PhpParser\Node\Expr\New_ ||
            ($secondArgValue instanceof \PhpParser\Node\Expr\StaticCall && $this->isName($secondArgValue->name, 'create'))
        ) {
            $shouldRename = true;
        } elseif ($type instanceof ObjectType && ! $this->isObjectType($secondArgValue, new ObjectType('SilverStripe\Forms\FieldList'))) {
            $shouldRename = true; // Known object, but definitively not a FieldList
        } elseif ($type->isString()->yes()) {
            $shouldRename = true;
        }

        // 5. Naming Heuristics for MixedType Unresolved variables
        $nameToCheck = null;
        if ($secondArgValue instanceof Variable && is_string($secondArgValue->name)) {
            $nameToCheck = $secondArgValue->name;
        } elseif ($secondArgValue instanceof PropertyFetch && $secondArgValue->name instanceof Identifier) {
            $nameToCheck = $secondArgValue->name->toString();
        }

        if ($nameToCheck !== null) {
            if (preg_match('/fields$/i', $nameToCheck)) {
                return null; // Assumed Array/FieldList -> Force ignore
            }
            if (preg_match('/field$/i', $nameToCheck)) {
                $shouldRename = true; // Assumed Object -> Rename
            }
        }

        // 6. Execute Rename
        if ($shouldRename) {
            $methodCall->name = new Identifier($targetMethod);
            return $node; // Return outer Expression to save changes
        }

        // 7. MIXED/UNKNOWN TYPE: Inject safe TODO note on the Expression statement
        $todoLine = $this->buildTodoLine(
            'FieldList', 
            $methodName, 
            "Mixed type detected. If this is a single field/string, change to {$targetMethod}() manually."
        );
        
        $this->appendTodoDocCommentSafely($node, $todoLine);

        return $node;
    }
}
