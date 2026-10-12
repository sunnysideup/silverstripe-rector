<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\Forms;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
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
            'Renames Value() and value() methods to getValue() for classes extending FormField. 
            See https://docs.silverstripe.org/en/6/changelogs/6.0.0/#formfield-value',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyField extends \SilverStripe\Forms\FormField
{
    public function Value()
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
    public function getValue()
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
        // Target both the class declaration and method calls
        return [Class_::class, MethodCall::class];
    }

    /**
     * @param Class_|MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        // 1. Handle Class Declarations (Renaming the method definition)
        if ($node instanceof Class_) {
            if (! $this->isObjectType($node, new ObjectType('SilverStripe\Forms\FormField'))) {
                return null;
            }

            $hasChanged = false;
            foreach ($node->getMethods() as $classMethod) {
                if ($this->isName($classMethod->name, 'Value') || $this->isName($classMethod->name, 'value')) {
                    $classMethod->name = new Identifier('getValue');
                    $hasChanged = true;
                }
            }

            return $hasChanged ? $node : null;
        }

        // 2. Handle Method Calls (Renaming $field->Value() to $field->getValue())
        if ($node instanceof MethodCall) {
            if (! $this->isObjectType($node->var, new ObjectType('SilverStripe\Forms\FormField'))) {
                return null;
            }

            if ($this->isName($node->name, 'Value') || $this->isName($node->name, 'value')) {
                $node->name = new Identifier('getValue');
                return $node;
            }
        }

        return null;
    }
}
