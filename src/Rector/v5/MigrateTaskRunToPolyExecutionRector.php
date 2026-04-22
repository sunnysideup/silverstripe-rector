<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\v5;

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
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\Namespace_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

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
$task = MyTask::create();
$definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
$input = new \Symfony\Component\Console\Input\ArrayInput(['MyParam' => true], $definition);
$output = \SilverStripe\PolyExecution\PolyOutput::create('ansi');
$task->run($input, $output);
CODE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        // Target blocks of statements rather than single expressions
        return [
            ClassMethod::class,
            Function_::class,
            Closure::class,
            Namespace_::class,
        ];
    }

    /**
     * @param ClassMethod|Function_|Closure|Namespace_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->stmts === null) {
            return null;
        }

        $stmts = $node->stmts;
        $state = []; // Map: varName => ['isTask' => bool, 'setters' => [ stmtIndex => ArrayItem ]]

        foreach ($stmts as $i => $stmt) {
            if (!$stmt instanceof Expression) {
                continue;
            }

            $expr = $stmt->expr;

            // Track assignment to deduce if variable holds a Task
            if ($expr instanceof Assign && $expr->var instanceof Variable) {
                $varName = $this->getName($expr->var);
                if ($varName) {
                    $state[$varName] = [
                        'isTask' => $this->isObjectType($expr->expr, new ObjectType('SilverStripe\Dev\BuildTask')) 
                                 || $this->isObjectType($expr->expr, new ObjectType('App\Tasks\MyTask')),
                        'setters' => []
                    ];
                }
                continue;
            }

            // Track Method Calls
            if ($expr instanceof MethodCall && $expr->var instanceof Variable) {
                $varName = $this->getName($expr->var);
                $methodName = $this->getName($expr->name);

                if (!$varName || !$methodName) {
                    continue;
                }

                // Initialise state if not explicitly assigned earlier (e.g., injected via method parameter)
                if (!isset($state[$varName])) {
                    $state[$varName] = [
                        'isTask' => $this->isObjectType($expr->var, new ObjectType('SilverStripe\Dev\BuildTask'))
                                 || $this->isObjectType($expr->var, new ObjectType('App\Tasks\MyTask')),
                        'setters' => []
                    ];
                }

                // Collect Setters
                if ($state[$varName]['isTask'] && str_starts_with($methodName, 'set')) {
                    $key = str_replace('set', '', $methodName);
                    $args = $expr->getArgs();
                    if (isset($args[0])) {
                        $state[$varName]['setters'][$i] = new ArrayItem($args[0]->value, new String_($key));
                    }
                } 
                // Mutate on run()
                elseif ($state[$varName]['isTask'] && $methodName === 'run') {
                    $params = array_values($state[$varName]['setters']);
                    $setterIndices = array_keys($state[$varName]['setters']);
                    
                    $newStmts = $this->generatePolyExecutionNodes($expr->var, $params, $expr);
                    
                    // Remove setter statements
                    foreach ($setterIndices as $idx) {
                        unset($stmts[$idx]);
                    }
                    
                    // Replace the run() statement with our block
                    array_splice($stmts, array_search($stmt, $stmts, true), 1, $newStmts);
                    
                    // Reassign and return immediately to allow Rector to traverse cleanly from the top
                    $node->stmts = array_values($stmts);
                    return $node;
                }
            }
            
            // Handle inline instantiation: (new MyTask())->run()
            if ($expr instanceof MethodCall && $expr->var instanceof New_) {
                if ($this->getName($expr->name) === 'run' && (
                    $this->isObjectType($expr->var, new ObjectType('SilverStripe\Dev\BuildTask')) ||
                    $this->isObjectType($expr->var, new ObjectType('App\Tasks\MyTask'))
                )) {
                    $newStmts = $this->generatePolyExecutionNodes($expr->var, [], $expr);
                    array_splice($stmts, array_search($stmt, $stmts, true), 1, $newStmts);
                    $node->stmts = array_values($stmts);
                    return $node;
                }
            }
        }

        return null;
    }

    /**
     * @param Node\Expr $taskExpr
     * @param ArrayItem[] $params
     * @param MethodCall $originalRunCall
     * @return Node\Stmt[]
     */
    private function generatePolyExecutionNodes(Node\Expr $taskExpr, array $params, MethodCall $originalRunCall): array
    {
        $definitionVar = new Variable('definition');
        $inputVar = new Variable('input');
        $outputVar = new Variable('output');

        $nodes = [];

        // $definition = new InputDefinition($task->getOptions());
        $nodes[] = new Expression(new Assign($definitionVar, new New_(
            new Node\Name\FullyQualified('Symfony\Component\Console\Input\InputDefinition'),
            [new Node\Arg(new MethodCall($taskExpr, 'getOptions'))]
        )));

        // $input = new ArrayInput([...], $definition);
        $nodes[] = new Expression(new Assign($inputVar, new New_(
            new Node\Name\FullyQualified('Symfony\Component\Console\Input\ArrayInput'),
            [
                new Node\Arg(new Array_($params)),
                new Node\Arg($definitionVar)
            ]
        )));

        // $output = PolyOutput::create(PolyOutput::FORMAT_ANSI);
        $nodes[] = new Expression(new Assign($outputVar, new StaticCall(
            new Node\Name\FullyQualified('SilverStripe\PolyExecution\PolyOutput'),
            'create',
            [new Node\Arg(new Node\Expr\ClassConstFetch(new Node\Name\FullyQualified('SilverStripe\PolyExecution\PolyOutput'), 'FORMAT_ANSI'))]
        )));

        // $task->run($input, $output);
        $originalRunCall->args = [
            new Node\Arg($inputVar),
            new Node\Arg($outputVar)
        ];
        $nodes[] = new Expression($originalRunCall);

        return $nodes;
    }
}
