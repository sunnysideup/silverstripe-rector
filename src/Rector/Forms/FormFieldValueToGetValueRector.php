<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\Forms;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class FormFieldValueToGetValueRector extends AbstractRector
{
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Renames Value() and value() methods to getValue(): mixed for classes extending FormField. 
            See https://docs.silverstripe.org/en/6/changelogs/6.0.0/#formfield-value',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyField extends \SilverStripe\Forms\FormField
{
    protected function Value()
    {
        return 'test';
    }
}

$field = new MyField('Test');
$val = $field->value();
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class MyField extends \SilverStripe\Forms\FormField
{
    public function getValue(): mixed
    {
        return 'test';
    }
}

$field = new MyField('Test');
$val = $field->getValue();
CODE_SAMPLE
                )
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Class_::class, MethodCall::class, NullsafeMethodCall::class];
    }

    /**
     * @param Class_|MethodCall|NullsafeMethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Class_) {
            if (! $this->isObjectType($node, new ObjectType('SilverStripe\Forms\FormField'))) {
                return null;
            }

            $hasChanged = false;
            foreach ($node->getMethods() as $classMethod) {
                // Target Value, value, and getValue (to fix incomplete manual upgrades)
                if ($this->isNames($classMethod->name, ['Value', 'value', 'getValue'])) {
                    
                    // 1. Rename method
                    if (! $this->isName($classMethod->name, 'getValue')) {
                        $classMethod->name = new Identifier('getValue');
                        $hasChanged = true;
                    }

                    // 2. Enforce 'mixed' return type
                    if ($classMethod->returnType === null || ! $this->isName($classMethod->returnType, 'mixed')) {
                        $classMethod->returnType = new Identifier('mixed');
                        $hasChanged = true;
                    }

                    // 3. Enforce 'public' visibility
                    if (($classMethod->flags & Modifiers::PUBLIC) !== Modifiers::PUBLIC) {
                        $classMethod->flags = ($classMethod->flags & ~Modifiers::PROTECTED & ~Modifiers::PRIVATE) | Modifiers::PUBLIC;
                        $hasChanged = true;
                    }
                }
            }

            return $hasChanged ? $node : null;
        }

        if ($node instanceof MethodCall || $node instanceof NullsafeMethodCall) {
            if (! $this->isObjectType($node->var, new ObjectType('SilverStripe\Forms\FormField'))) {
                return null;
            }

            // Only rename the calls
            if ($this->isNames($node->name, ['Value', 'value'])) {
                $node->name = new Identifier('getValue');
                return $node;
            }
        }

        return null;
    }
}
