<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\Dev;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Modifiers;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Rector\PhpParser\Node\BetterNodeFinder;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Netwerkstatt\SilverstripeRector\Tests\Dev\BuildTaskToExecuteRector\BuildTaskToExecuteRectorTest
 */
final class BuildTaskToExecuteRector extends AbstractRector
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder
    ) {}

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Changes BuildTask run() to protected execute() with proper CLI types', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class MyTask extends \SilverStripe\Dev\BuildTask {
    public function run($request) {
        echo "done";
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class MyTask extends \SilverStripe\Dev\BuildTask {
    protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \SilverStripe\Console\PolyOutput $output): int {
        echo "done";
        return 0;
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node->name, 'run')) {
            return null;
        }

        $class = $this->betterNodeFinder->findParentType($node, Class_::class);
        if (!$class || !$this->isObjectType($class, new ObjectType('SilverStripe\Dev\BuildTask'))) {
            return null;
        }

        // 1. Rename and visibility
        $node->name = new Node\Identifier('execute');
        $node->flags = Modifiers::PROTECTED;

        // 2. Set Parameters (InputInterface $input, PolyOutput $output)
        $node->params = [
            new Node\Param(new Node\Variable('input'), null, new Node\Name\FullyQualified('Symfony\Component\Console\Input\InputInterface')),
            new Node\Param(new Node\Variable('output'), null, new Node\Name\FullyQualified('SilverStripe\Console\PolyOutput')),
        ];

        // 3. Set Return Type
        $node->returnType = new Node\Identifier('int');

        // 4. Ensure return statement in body to satisfy 'int' return type
        if ($node->stmts !== null) {
            $hasReturn = false;
            foreach ($node->stmts as $stmt) {
                if ($stmt instanceof Return_) {
                    $hasReturn = true;
                    break;
                }
            }

            if (!$hasReturn) {
                $node->stmts[] = new Return_(new Int_(0));
            }
        }

        return $node;
    }
}
