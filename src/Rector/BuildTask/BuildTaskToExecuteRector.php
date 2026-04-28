<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\BuildTask;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Modifiers;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Netwerkstatt\SilverstripeRector\Tests\BuildTask\BuildTaskToExecuteRector\BuildTaskToExecuteRectorTest
 */
final class BuildTaskToExecuteRector extends AbstractRector
{
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
    protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \SilverStripe\PolyExecution\PolyOutput $output): int
    {
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
        // Target the Class itself, not the method, to avoid upwards traversal
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isObjectType($node, new ObjectType('SilverStripe\Dev\BuildTask'))) {
            return null;
        }

        $hasChanged = false;

        foreach ($node->getMethods() as $method) {
            if (!$this->isName($method->name, 'run')) {
                continue;
            }

            // 1. Rename and visibility
            $method->name = new Node\Identifier('execute');
            $method->flags = Modifiers::PROTECTED;

            // 2. Set Parameters
            $method->params = [
                new Node\Param(new Node\Expr\Variable('input'), null, new Node\Name\FullyQualified('Symfony\Component\Console\Input\InputInterface')),
                new Node\Param(new Node\Expr\Variable('output'), null, new Node\Name\FullyQualified('SilverStripe\PolyExecution\PolyOutput')),
            ];

            // 3. Set Return Type
            $method->returnType = new Node\Identifier('int');

            // 4. Handle body and return type compliance
            if ($method->stmts === null) {
                $method->stmts = [new Return_(new Int_(0))];
            } else {
                $hasReturn = false;
                foreach ($method->stmts as $stmt) {
                    if ($stmt instanceof Return_) {
                        $hasReturn = true;
                        break;
                    }
                }

                if (!$hasReturn) {
                    $method->stmts[] = new Return_(new Int_(0));
                }
            }

            $hasChanged = true;
        }

        return $hasChanged ? $node : null;
    }
}
