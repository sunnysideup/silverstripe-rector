<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\CMS;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Block;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\FunctionLike;
use PhpParser\Node\Stmt\Namespace_;
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
        // Target any Statement so we can return an array of statements to replace it
        return [Stmt::class];
    }

    /**
     * @param Stmt $node
     */
    public function refactor(Node $node): ?array
    {
        // Skip structural nodes that shouldn't be replaced by an array of inline statements
        if (
            $node instanceof ClassLike ||
            $node instanceof FunctionLike ||
            $node instanceof Namespace_ ||
            $node instanceof Block
        ) {
            return null;
        }

        $hasChanged = false;

        $this->traverseNodesWithCallable($node, function (Node $subNode) use (&$hasChanged) {
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

        // Return array of nodes: replaces the original single statement with these three
        return [
            $stmt1,
            $stmt2,
            $node
        ];
    }
}
