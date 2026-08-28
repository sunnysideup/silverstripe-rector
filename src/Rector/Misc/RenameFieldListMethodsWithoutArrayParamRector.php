<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\Misc;

use Netwerkstatt\SilverstripeRector\Traits\MethodHelper;
use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
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
// @TODO SSU RECTOR UPGRADE TASK: FieldList::addFieldsToTab() - Mixed type detected. If this is a single field, change to addFieldToTab() manually.
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

        // 1. Explicit AST Checks
        if ($secondArgValue instanceof Array_) {
            return null; // Clearly an array, leave as plural
        }

        if ($secondArgValue instanceof String_) {
            $node->name = new Identifier($targetMethod);
            return $node;
        }

        if ($this->isObjectType($secondArgValue, new ObjectType('SilverStripe\Forms\FieldList'))) {
            return null; // FieldList acts as an iterable array of fields
        }

        if (
            $secondArgValue instanceof \PhpParser\Node\Expr\New_ ||
            ($secondArgValue instanceof \PhpParser\Node\Expr\StaticCall && $this->isName($secondArgValue->name, 'create'))
        ) {
            $node->name = new Identifier($targetMethod);
            return $node;
        }

        // 2. Strict Type Inference
        $type = $this->getType($secondArgValue);

        if ($type->isString()->yes() || $type->isObject()->yes()) {
            $node->name = new Identifier($targetMethod);
            return $node;
        }

        if ($type->isArray()->yes() || $type->isIterable()->yes()) {
            return null;
        }

        if ($type->isArray()->no() && ! $type instanceof MixedType) {
            $node->name = new Identifier($targetMethod);
            return $node;
        }

        // 3. MIXED/UNKNOWN TYPE: Inject a safe TODO note
        $todoLine = $this->buildTodoLine(
            'FieldList', 
            $methodName, 
            "Mixed type detected. If this is a single field/string, change to {$targetMethod}() manually."
        );
        
        $this->appendTodoDocCommentSafely($node, $todoLine);

        return $node;
    }
}
