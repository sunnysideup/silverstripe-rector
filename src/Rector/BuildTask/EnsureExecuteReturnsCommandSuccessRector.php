<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\BuildTask;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class EnsureExecuteReturnsCommandSuccessRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Ensures BuildTask execute() methods always finish by returning Command::SUCCESS', [
            new CodeSample(
                <<<'CODE'
protected function execute(InputInterface $input, PolyOutput $output): int 
{
    $this->doSomething();
    return true;
}
CODE
                ,
                <<<'CODE'
protected function execute(InputInterface $input, PolyOutput $output): int 
{
    $this->doSomething();
    return \Symfony\Component\Console\Command\Command::SUCCESS;
}
CODE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        // Target the class itself so we can cleanly grab the method and its statements
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isTaskClass($node)) {
            return null;
        }

        $executeMethod = $node->getMethod('execute');
        if (!$executeMethod || $executeMethod->stmts === null) {
            return null;
        }

        $stmts = $executeMethod->stmts;
        $successExpr = new ClassConstFetch(
            new FullyQualified('Symfony\Component\Console\Command\Command'),
            'SUCCESS'
        );

        // Handle empty execute method
        if (count($stmts) === 0) {
            $executeMethod->stmts[] = new Return_($successExpr);
            return $node;
        }

        $lastStmtKey = array_key_last($stmts);
        $lastStmt = $stmts[$lastStmtKey];

        // If the last line is a return statement
        if ($lastStmt instanceof Return_) {
            // Idempotency: Ignore if it's already Command::SUCCESS
            if ($this->isCommandSuccess($lastStmt->expr)) {
                return null;
            }
            
            // Replace the return expression
            $lastStmt->expr = $successExpr;
            return $node;
        }

        // If the last line is NOT a return statement, append one
        $stmts[] = new Return_($successExpr);
        $executeMethod->stmts = $stmts;

        return $node;
    }

    private function isTaskClass(Class_ $class): bool
    {
        if ($this->isObjectType($class, new ObjectType('SilverStripe\Dev\BuildTask'))) {
            return true;
        }

        // Fallback for static analysis blindness in fixtures
        if ($class->extends instanceof Node\Name) {
            $parentClass = $this->getName($class->extends);
            if ($parentClass === 'BuildTask' || $parentClass === '\SilverStripe\Dev\BuildTask') {
                return true;
            }
        }

        return false;
    }

    private function isCommandSuccess(?Node\Expr $expr): bool
    {
        if (!$expr instanceof ClassConstFetch) {
            return false;
        }
        
        if (!$expr->class instanceof Node\Name) {
            return false;
        }
        
        return $this->getName($expr->class) === 'Symfony\Component\Console\Command\Command' 
            && $this->getName($expr->name) === 'SUCCESS';
    }
}
