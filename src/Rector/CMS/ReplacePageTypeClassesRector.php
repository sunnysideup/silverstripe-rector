<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\CMS;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\FileWithoutNamespace;
use PhpParser\Node\Stmt\Namespace_;
use PHPStan\Type\ObjectType;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\PhpParser\Enum\NodeGroup;
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
CODE_SAMPLE,
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
        return NodeGroup::STMTS_AWARE;
    }

    /**
     * @param StmtsAwareInterface $node
     */
    public function refactor(Node $node): ?Node
    {
        // Prevent the rule from bubbling setup variables to the global, namespace, or class level
        if (
            $node instanceof Namespace_ ||
            $node instanceof FileWithoutNamespace ||
            $node instanceof ClassLike ||
            $node instanceof Declare_
        ) {
            return null;
        }

        if ($node->stmts === null) {
            return null;
        }

        $hasChanged = false;
        $newStmts = [];

        foreach ($node->stmts as $stmt) {
            $foundTarget = false;

            // Traverse the parent statement directly. This ensures that when the StaticCall 
            // is replaced, PHP-Parser updates the reference on the parent Stmt (e.g., Return_)
            $this->traverseNodesWithCallable($stmt, function (Node $subNode) use (&$foundTarget) {
                if (! $subNode instanceof StaticCall) {
                    return null;
                }

                if (! $this->isName($subNode->name, 'page_type_classes')) {
                    return null;
                }

                if (! $this->isObjectType($subNode->class, new ObjectType('SilverStripe\CMS\Model\SiteTree')) && ! $this->isName($subNode->class, 'SiteTree')) {
                    return null;
                }

                $foundTarget = true;

                // Mutate the tree in-place by returning the new variable
                return new Variable('classes');
            });

            if ($foundTarget) {
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

                // Splice our setup statements right above the successfully mutated statement
                $newStmts[] = $stmt1;
                $newStmts[] = $stmt2;
                $newStmts[] = $stmt;
                $hasChanged = true;
            } else {
                $newStmts[] = $stmt;
            }
        }

        if ($hasChanged) {
            $node->stmts = $newStmts;
            return $node;
        }

        return null;
    }
}
