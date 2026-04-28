<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\Control;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Netwerkstatt\SilverstripeRector\Tests\Control\UpdateControllerRenderSignatureRector\UpdateControllerRenderSignatureRectorTest
 */
final class UpdateControllerRenderSignatureRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Updates render() method signature in Controller subclasses to use $params = null and DBHTMLText return type.', [
            new CodeSample(
                <<<'CODE'
class MyController extends \SilverStripe\Control\Controller {
    public function render($context = null) {
        return null;
    }
}
CODE
                ,
                <<<'CODE'
class MyController extends \SilverStripe\Control\Controller {
    public function render($params = null): \SilverStripe\ORM\FieldType\DBHTMLText {
        return null;
    }
}
CODE
            ),
        ]);
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
        if (!$this->isObjectType($node, new ObjectType('SilverStripe\Control\Controller'))) {
            return null;
        }

        $renderMethod = $node->getMethod('render');
        if (!$renderMethod instanceof ClassMethod) {
            return null;
        }

        $hasChanged = false;

        // 1. Update Return Type
        if ($renderMethod->returnType === null || !$this->isName($renderMethod->returnType, 'SilverStripe\ORM\FieldType\DBHTMLText')) {
            $renderMethod->returnType = new \PhpParser\Node\Name\FullyQualified('SilverStripe\ORM\FieldType\DBHTMLText');
            $hasChanged = true;
        }

        // 2. Update Parameters
        $needsParamUpdate = true;
        $params = $renderMethod->params;
        
        if (count($params) === 1) {
            $firstParam = $params[0];
            if ($firstParam->default instanceof \PhpParser\Node\Expr\ConstFetch) {
                $defaultName = $this->getName($firstParam->default->name);
                if (
                    $defaultName !== null && 
                    strtolower($defaultName) === 'null' &&
                    $this->isName($firstParam->var, 'params') &&
                    $firstParam->type === null
                ) {
                    $needsParamUpdate = false;
                }
            }
        }

        if ($needsParamUpdate) {
            $renderMethod->params = [
                new \PhpParser\Node\Param(
                    new \PhpParser\Node\Expr\Variable('params'),
                    new \PhpParser\Node\Expr\ConstFetch(new \PhpParser\Node\Name('null'))
                )
            ];
            $hasChanged = true;
        }

        return $hasChanged ? $node : null;
    }
}
