<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\Control;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddParentConstructToControllerRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Adds missing parent::__construct() call to Controller subclasses',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class PropertyHolderController extends Controller
{
    public function __construct(?PropertyHolder $model = null)
    {
        $this->locatable = $this;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class PropertyHolderController extends Controller
{
    public function __construct(?PropertyHolder $model = null)
    {
        parent::__construct($model);
        $this->locatable = $this;
    }
}
CODE_SAMPLE
                )
            ]
        );
    }

    public function getNodeTypes(): array
    {
        // Target Class_ to efficiently evaluate inheritance context
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        // Verify class extends SilverStripe\Control\Controller
        if (! $this->isObjectType($node, new ObjectType('SilverStripe\Control\Controller'))) {
            return null;
        }

        $constructMethod = $node->getMethod('__construct');

        if (! $constructMethod instanceof ClassMethod || $constructMethod->stmts === null) {
            return null;
        }

        // Check if parent::__construct is already being called
        $hasParentCall = false;
        $this->traverseNodesWithCallable($constructMethod->stmts, function (Node $subNode) use (&$hasParentCall) {
            if ($subNode instanceof StaticCall && 
                $this->isName($subNode->class, 'parent') && 
                $this->isName($subNode->name, '__construct')
            ) {
                $hasParentCall = true;
            }
            return null;
        });

        if ($hasParentCall) {
            return null;
        }

        // Map subclass constructor parameters to arguments for the parent call
        $args = [];
        foreach ($constructMethod->params as $param) {
            if ($param->var instanceof Variable && is_string($param->var->name)) {
                $args[] = new Arg(new Variable($param->var->name));
            }
        }

        // Build: parent::__construct($arg1, $arg2, ...)
        $parentConstructCall = new StaticCall(
            new Name('parent'),
            new Identifier('__construct'),
            $args
        );

        $expression = new Expression($parentConstructCall);

        // Inject the call at the very top of the method
        array_unshift($constructMethod->stmts, $expression);

        return $node;
    }
}
