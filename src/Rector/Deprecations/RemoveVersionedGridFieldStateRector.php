<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\Deprecations;

use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Nop;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveVersionedGridFieldStateRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Removes addComponent() calls for deprecated VersionedGridFieldState from any context',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
return $this->config->doSomething()->addComponent(VersionedGridFieldState::class);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
// VersionedGridFieldState is Deprecated, consider Using:
// $dataColumns = $config->getComponentByType(GridFieldDataColumns::class);
// Show the flags against a specific column (e.g. if you don't have a Title column)
// $dataColumns->setColumnsForStatusFlag(['Name']);
return $this->config->doSomething();
CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Stmt::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (! $node instanceof Stmt) {
            return null;
        }

        $traverser = new NodeTraverser();
        
        $visitor = new class() extends NodeVisitorAbstract {
            public bool $hasChanged = false;

            public function enterNode(Node $n): ?Node
            {
                if (! $n instanceof MethodCall) {
                    return null;
                }

                // Native PHP-Parser check instead of AbstractRector::isName()
                if (! $n->name instanceof Identifier || $n->name->toString() !== 'addComponent') {
                    return null;
                }

                $arg = $n->getArgs()[0] ?? null;
                if (! $arg instanceof Arg) {
                    return null;
                }

                $val = $arg->value;
                $isTarget = false;

                if ($val instanceof New_) {
                    $isTarget = $this->isVersionedState($val->class);
                } elseif ($val instanceof StaticCall) {
                    if ($val->name instanceof Identifier && $val->name->toString() === 'create') {
                        $isTarget = $this->isVersionedState($val->class);
                    }
                } elseif ($val instanceof ClassConstFetch) {
                    if ($val->name instanceof Identifier && $val->name->toString() === 'class') {
                        $isTarget = $this->isVersionedState($val->class);
                    }
                }

                if ($isTarget) {
                    $this->hasChanged = true;
                    return clone $n->var;
                }

                return null;
            }

            private function isVersionedState(Node $classNode): bool {
                // Native PHP-Parser check instead of AbstractRector::getName()
                if ($classNode instanceof Name) {
                    return str_ends_with($classNode->toString(), 'VersionedGridFieldState');
                }
                return false;
            }
        };

        $traverser->addVisitor($visitor);
        $clonedNode = clone $node;
        
        $newStmts = $traverser->traverse([$clonedNode]);
        $newNode = $newStmts[0] ?? null;

        if (! $visitor->hasChanged || ! $newNode instanceof Stmt) {
            return null;
        }

        $commentText = "// VersionedGridFieldState is Deprecated, consider Using:\n" .
                       "// \$dataColumns = \$config->getComponentByType(GridFieldDataColumns::class);\n" .
                       "// Show the flags against a specific column (e.g. if you don't have a Title column)\n" .
                       "// \$dataColumns->setColumnsForStatusFlag(['Name']);";
        
        $comment = new Comment($commentText);
        $existingComments = $node->getComments();

        if ($newNode instanceof Expression) {
            if ($newNode->expr instanceof Variable || $newNode->expr instanceof PropertyFetch) {
                $nop = new Nop();
                $nop->setAttribute('comments', array_merge($existingComments, [$comment]));
                return $nop;
            }
        }

        $newNode->setAttribute('comments', array_merge($existingComments, [$comment]));

        return $newNode;
    }
}
