<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\CMS;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\While_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplacePageTypeClassesRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replaces deprecated SiteTree::page_type_classes() with ClassInfo::getValidSubClasses and invokeWithExtensions',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
protected function getClassList()
{
    return SiteTree::page_type_classes();
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
protected function getClassList()
{
    $classes = \SilverStripe\Core\ClassInfo::getValidSubClasses(\SilverStripe\CMS\Model\SiteTree::class);
    \SilverStripe\ORM\DataObject::singleton(\SilverStripe\CMS\Model\SiteTree::class)->invokeWithExtensions('updateAllowedSubClasses', $classes);
    return $classes;
}
CODE_SAMPLE
                )
            ]
        );
    }

    public function getNodeTypes(): array
    {
        // Explicitly target only the executable statements that might contain the call
        // This prevents the setup nodes from bubbling up to the Class or Namespace scope
        return [
            Expression::class,
            Return_::class,
            If_::class,
            While_::class,
            Do_::class,
            Switch_::class,
            Foreach_::class,
        ];
    }

    /**
     * @param Expression|Return_|If_|While_|Do_|Switch_|Foreach_ $node
     */
    public function refactor(Node $node): ?array
    {
        $targetExpr = null;

        // Isolate the traversal to the specific condition or expression 
        // to avoid recursively scanning into child block statements.
        if ($node instanceof Expression) {
            $targetExpr = $node->expr;
        } elseif ($node instanceof Return_ && $node->expr !== null) {
            $targetExpr = $node->expr;
        } elseif ($node instanceof If_) {
            $targetExpr = $node->cond;
        } elseif ($node instanceof While_) {
            $targetExpr = $node->cond;
        } elseif ($node instanceof Do_) {
            $targetExpr = $node->cond;
        } elseif ($node instanceof Switch_) {
            $targetExpr = $node->cond;
        } elseif ($node instanceof Foreach_) {
            $targetExpr = $node->expr;
        }

        if ($targetExpr === null) {
            return null;
        }

        $hasChanged = false;

        $this->traverseNodesWithCallable($targetExpr, function (Node $subNode) use (&$hasChanged) {
            if (! $subNode instanceof StaticCall) {
                return null;
            }

            if (! $this->isName($subNode->name, 'page_type_classes')) {
                return null;
            }

            if (! $this->isObjectType($subNode->class, new ObjectType('SilverStripe\CMS\Model\SiteTree')) && ! $this->isName($subNode->class, 'SiteTree')) {
                return null;
            }

            $hasChanged = true;
            
            // Swap out the StaticCall with our target variable
            return new Variable('classes');
        });

        if (! $hasChanged) {
            return null;
        }

        $siteTreeClassConst = $this->nodeFactory->createClassConstFetch('SilverStripe\CMS\Model\SiteTree', 'class');
        $classesVar = new Variable('classes');

        // 1. $classes = ClassInfo::getValidSubClasses(SiteTree::class);
        $assign = new Assign(
            $classesVar,
            $this->nodeFactory->createStaticCall('SilverStripe\Core\ClassInfo', 'getValidSubClasses', [$siteTreeClassConst])
        );
        $stmt1 = new Expression($assign);

        // 2. DataObject::singleton(SiteTree::class)->invokeWithExtensions('updateAllowedSubClasses', $classes);
        $singletonCall = $this->nodeFactory->createStaticCall('SilverStripe\ORM\DataObject', 'singleton', [$siteTreeClassConst]);
        $invokeCall = $this->nodeFactory->createMethodCall($singletonCall, 'invokeWithExtensions', [
            'updateAllowedSubClasses',
            $classesVar
        ]);
        $stmt2 = new Expression($invokeCall);

        // Return array of nodes: seamlessly splices the setup above the mutated original statement
        return [
            $stmt1,
            $stmt2,
            $node
        ];
    }
}
