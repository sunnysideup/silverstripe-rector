<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\Forms;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class FormFieldValidateSignatureRector extends AbstractRector
{
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Migrates validate() method on FormField to return ValidationResult and use addError(). 
            See https://docs.silverstripe.org/en/6/changelogs/6.0.0/#formfield-validation',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyField extends \SilverStripe\Forms\FormField
{
    public function validate($validator)
    {
        $valid = parent::validate($validator);
        if (true) {
            $validator->validationError($this->name, 'Not unique', 'validation');
            $valid = false;
        }
        return $valid;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class MyField extends \SilverStripe\Forms\FormField
{
    public function validate(): \SilverStripe\ORM\ValidationResult
    {
        $valid = parent::validate();
        if (true) {
            $valid->addError('Not unique', 'validation');
        }
        return $valid;
    }
}
CODE_SAMPLE
                )
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isObjectType($node, new ObjectType('SilverStripe\Forms\FormField'))) {
            return null;
        }

        $method = $node->getMethod('validate');
        if (! $method) {
            return null;
        }

        $hasChanged = false;

        // 1. Update signature (remove parameters)
        if (count($method->params) > 0) {
            $method->params = [];
            $hasChanged = true;
        }

        // 2. Update return type to \SilverStripe\ORM\ValidationResult
        if ($method->returnType === null || ! $this->isName($method->returnType, 'SilverStripe\ORM\ValidationResult')) {
            $method->returnType = new FullyQualified('SilverStripe\ORM\ValidationResult');
            $hasChanged = true;
        }

        // 3. Traverse the inside of the method to rewrite calls
        if ($method->stmts !== null) {
            $this->traverseNodesWithCallable($method->stmts, function (Node $subNode) use (&$hasChanged) {
                
                // A. Fix: parent::validate($validator) -> parent::validate()
                if ($subNode instanceof StaticCall && $this->isName($subNode->class, 'parent') && $this->isName($subNode->name, 'validate')) {
                    if (count($subNode->args) > 0) {
                        $subNode->args = [];
                        $hasChanged = true;
                        return $subNode;
                    }
                }

                // B. Fix: $validator->validationError(...) -> $valid->addError(...)
                if ($subNode instanceof MethodCall && $this->isName($subNode->name, 'validationError')) {
                    $subNode->var = new Variable('valid');
                    $subNode->name = new Identifier('addError');
                    
                    // Shift arguments (keep only message and type, drop fieldName)
                    $args = $subNode->getArgs();
                    $newArgs = [];
                    if (isset($args[1])) {
                        $newArgs[] = $args[1];
                    }
                    if (isset($args[2])) {
                        $newArgs[] = $args[2];
                    }
                    
                    $subNode->args = $newArgs;
                    $hasChanged = true;
                    return $subNode;
                }

                // C. Fix: Delete "$valid = false;"
                if ($subNode instanceof Expression && $subNode->expr instanceof Assign) {
                    $assign = $subNode->expr;
                    if ($assign->var instanceof Variable && $this->isName($assign->var, 'valid')) {
                        if ($assign->expr instanceof ConstFetch && $this->isName($assign->expr->name, 'false')) {
                            $hasChanged = true;
                            // Returning NodeVisitor::REMOVE_NODE tells the Traverser to delete this statement entirely
                            return NodeVisitor::REMOVE_NODE;
                        }
                    }
                }

                return null;
            });
        }

        return $hasChanged ? $node : null;
    }
}
