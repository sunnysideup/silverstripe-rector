<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\Deprecations;

use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
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
        
        $visitor = new class($node) extends NodeVisitorAbstract {
            public bool $hasChanged = false;
            private Stmt $rootNode;

            public function __construct(Stmt $rootNode)
            {
                $this->rootNode = $rootNode;
            }

            public function enterNode(Node $n): ?int
            {
                // Prevent traversing into nested statements. This inherently forces 
                // the comment to be placed on the most specific, nearest statement!
                if ($n instanceof Stmt && $n !== $this->rootNode) {
                    return NodeVisitor::DONT_TRAVERSE_CHILDREN;
                }

                if (! $n instanceof MethodCall) {
                    return null;
                }

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
                    // Replace the method call directly with its caller variable
                    return clone $n->var;
                }

                return null;
            }

            private function isVersionedState(Node $classNode): bool {
                if ($classNode instanceof Name) {
                    return str_ends_with($classNode->toString(), 'VersionedGridFieldState');
                }
                return false;
            }
        };

        $traverser->addVisitor($visitor);
        
        // Traverse the original node directly to safely modify expressions in place
        $traverser->traverse([$node]);

        if (! $visitor->hasChanged) {
            return null;
        }

        $commentText = "// VersionedGridFieldState is Deprecated, consider Using:\n" .
                       "// \$dataColumns = \$config->getComponentByType(GridFieldDataColumns::class);\n" .
                       "// Show the flags against a specific column (e.g. if you don't have a Title column)\n" .
                       "// \$dataColumns->setColumnsForStatusFlag(['Name']);";
        
        // Check to prevent duplicating comments on multiple passes (e.g., chained calls)
        $hasComment = false;
        if ($node->getComments() !== []) {
            foreach ($node->getComments() as $existingComment) {
                if (str_contains($existingComment->getText(), 'VersionedGridFieldState is Deprecated')) {
                    $hasComment = true;
                    break;
                }
            }
        }

        if (! $hasComment) {
            $comments = $node->getComments();
            $comments[] = new Comment($commentText);
            $node->setAttribute('comments', $comments);
        }

        return $node;
    }
}
