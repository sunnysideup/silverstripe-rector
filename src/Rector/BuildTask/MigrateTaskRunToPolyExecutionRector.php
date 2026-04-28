<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\BuildTask;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Netwerkstatt\SilverstripeRector\Tests\BuildTask\MigrateTaskRunToPolyExecutionRector\MigrateTaskRunToPolyExecutionRectorTest
 */
final class MigrateTaskRunToPolyExecutionRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrates old Task->run() syntax to PolyExecution with Input/Output objects', [
            new CodeSample(
                <<<'CODE'
$task = new MyTask();
$task->setMyParam(true);
$task->run(null);
CODE
                ,
                <<<'CODE'
$task = new MyTask();
$definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
$input = new \Symfony\Component\Console\Input\ArrayInput(['MyParam' => true], $definition);
$output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
$task->run($input, $output);
CODE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [
            StmtsAwareInterface::class,
        ];
    }

    /**
     * @param StmtsAwareInterface $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->stmts === null) {
            return null;
        }

        $newStmts = [];
        $hasChanged = false;
        $taskVars = []; 

        foreach ($node->stmts as $stmt) {
            $handled = false;

            if ($stmt instanceof Expression) {
                $expr = $stmt->expr;

                if ($expr instanceof Assign && $expr->var instanceof Variable) {
                    $varName = $this->getName($expr->var);
                    if ($varName !== null) {
                        $taskVars[$varName] = [
                            'isTask' => $this->isTaskType($expr->expr),
                            'setterIndices' => []
                        ];
                    }
                }

                if ($expr instanceof MethodCall && $expr->var instanceof Variable) {
                    $varName = $this->getName($expr->var);
                    $methodName = $this->getName($expr->name);

                    if ($varName !== null && $methodName !== null) {
                        if (!isset($taskVars[$varName])) {
                            $taskVars[$varName] = [
                                'isTask' => $this->isTaskType($expr->var),
                                'setterIndices' => []
                            ];
                        }

                        if ($taskVars[$varName]['isTask']) {
                            if (str_starts_with($methodName, 'set')) {
                                $key = substr($methodName, 3);
                                $args = $expr->getArgs();
                                if (isset($args[0])) {
                                    $newStmts[] = $stmt;
                                    $taskVars[$varName]['setterIndices'][array_key_last($newStmts)] = new ArrayItem($args[0]->value, new String_($key));
                                    $handled = true;
                                }
                            } elseif ($methodName === 'run') {
                                if (count($expr->getArgs()) < 2) {
                                    $params = array_values($taskVars[$varName]['setterIndices']);
                                    
                                    foreach (array_keys($taskVars[$varName]['setterIndices']) as $idx) {
                                        unset($newStmts[$idx]);
                                    }

                                    $replacementNodes = $this->generatePolyExecutionNodes($expr->var, $params);
                                    foreach ($replacementNodes as $replNode) {
                                        $newStmts[] = $replNode;
                                    }

                                    // Mutate existing statement args
                                    $expr->args = [
                                        new Node\Arg(new Variable('input')),
                                        new Node\Arg(new Variable('output'))
                                    ];
                                    
                                    $taskVars[$varName]['setterIndices'] = [];
                                    $handled = true;
                                    $hasChanged = true;
                                    $newStmts[] = $stmt; // Re-add the mutated statement
                                }
                            }
                        }
                    }
                } 
                elseif ($expr instanceof MethodCall && ($expr->var instanceof New_ || $expr->var instanceof StaticCall)) {
                    $methodName = $this->getName($expr->name);
                    if ($methodName === 'run' && $this->isTaskType($expr->var)) {
                        if (count($expr->getArgs()) < 2) {
                            $replacementNodes = $this->generatePolyExecutionNodes($expr->var, []);
                            foreach ($replacementNodes as $replNode) {
                                $newStmts[] = $replNode;
                            }

                            // Mutate existing statement args
                            $expr->args = [
                                new Node\Arg(new Variable('input')),
                                new Node\Arg(new Variable('output'))
                            ];

                            $handled = true;
                            $hasChanged = true;
                            $newStmts[] = $stmt; // Re-add the mutated statement
                        }
                    }
                }
            }

            if (!$handled) {
                $newStmts[] = $stmt;
            }
        }

        if ($hasChanged) {
            $node->stmts = array_values($newStmts);
            return $node;
        }

        return null;
    }

    private function isTaskType(Node $expr): bool
    {
        if ($this->isObjectType($expr, new ObjectType('SilverStripe\Dev\BuildTask'))) {
            return true;
        }

        if ($expr instanceof StaticCall && $this->isName($expr->name, 'create')) {
            return $this->isObjectType($expr->class, new ObjectType('SilverStripe\Dev\BuildTask'));
        }

        return false;
    }

    /**
     * @param Node\Expr $taskExpr
     * @param ArrayItem[] $params
     * @return Node\Stmt[]
     */
    private function generatePolyExecutionNodes(Node\Expr $taskExpr, array $params): array
    {
        $definitionVar = new Variable('definition');
        $inputVar = new Variable('input');
        $outputVar = new Variable('output');

        // IMPORTANT: We must avoid sharing the same AST node instance between getOptions() and run(), 
        // otherwise PHP-Parser creates an infinite printing loop.
        $taskExprForOptions = $taskExpr instanceof Variable 
            ? new Variable($taskExpr->name) 
            : clone $taskExpr;

        $nodes = [];

        $nodes[] = new Expression(new Assign($definitionVar, new New_(
            new Node\Name\FullyQualified('Symfony\Component\Console\Input\InputDefinition'),
            [new Node\Arg(new MethodCall($taskExprForOptions, 'getOptions'))]
        )));

        $nodes[] = new Expression(new Assign($inputVar, new New_(
            new Node\Name\FullyQualified('Symfony\Component\Console\Input\ArrayInput'),
            [
                new Node\Arg(new Array_($params)),
                new Node\Arg($definitionVar)
            ]
        )));

        $nodes[] = new Expression(new Assign($outputVar, new StaticCall(
            new Node\Name\FullyQualified('SilverStripe\PolyExecution\PolyOutput'),
            'create',
            [new Node\Arg(new Node\Expr\ClassConstFetch(new Node\Name\FullyQualified('SilverStripe\PolyExecution\PolyOutput'), 'FORMAT_ANSI'))]
        )));

        return $nodes;
    }
}
