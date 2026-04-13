<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\CMS;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\ObjectType;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
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
        // Target any node that contains an array of statements (ClassMethod, If_, Closure, etc.)
        return [StmtsAwareInterface::class];
    }

    /**
     * @param StmtsAwareInterface $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->stmts === null) {
            return null;
        }

        $hasChanged = false;
        $newStmts = [];

        foreach ($node->stmts as $stmt) {
            $foundTarget = false;

            // Traverse the current statement to find and replace our target StaticCall
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
                
                // Swap out the StaticCall with our target variable
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

                // Splice our setup statements right above the mutated statement
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
