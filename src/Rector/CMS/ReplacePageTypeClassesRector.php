<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\CMS;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Netwerkstatt\SilverstripeRector\Tests\CMS\ReplacePageTypeClassesRector\ReplacePageTypeClassesRectorTest
 */
final class ReplacePageTypeClassesRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replaces deprecated SiteTree::page_type_classes() with ClassInfo and invokeWithExtensions setup within the local method scope.
            See https://docs.silverstripe.org/en/6/changelogs/6.0.0/#silverstripecms',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyPage extends SiteTree {
    public function getList() {
        return SiteTree::page_type_classes();
    }
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
class MyPage extends SiteTree {
    public function getList() {
        $classes = \SilverStripe\Core\ClassInfo::getValidSubClasses(\SilverStripe\CMS\Model\SiteTree::class);
        \SilverStripe\ORM\DataObject::singleton(\SilverStripe\CMS\Model\SiteTree::class)->invokeWithExtensions('updateAllowedSubClasses', $classes);
        return $classes;
    }
}
CODE_SAMPLE
                )
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [ClassMethod::class, Function_::class];
    }

    /**
     * @param ClassMethod|Function_ $node
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
                return new Variable('classes');
            });

            if ($foundTarget) {
                $siteTreeClassConst = $this->nodeFactory->createClassConstFetch('SilverStripe\CMS\Model\SiteTree', 'class');
                $classesVar = new Variable('classes');

                $assign = new Assign(
                    $classesVar,
                    $this->nodeFactory->createStaticCall('SilverStripe\Core\ClassInfo', 'getValidSubClasses', [$siteTreeClassConst])
                );
                
                $singletonCall = $this->nodeFactory->createStaticCall('SilverStripe\ORM\DataObject', 'singleton', [$siteTreeClassConst]);
                $invokeCall = $this->nodeFactory->createMethodCall($singletonCall, 'invokeWithExtensions', [
                    'updateAllowedSubClasses',
                    $classesVar
                ]);

                $newStmts[] = new Expression($assign);
                $newStmts[] = new Expression($invokeCall);
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
