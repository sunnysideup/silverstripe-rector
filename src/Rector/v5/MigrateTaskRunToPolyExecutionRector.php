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
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node): ?array
    {
        $methodCall = $node->expr;
        if (!$methodCall instanceof MethodCall) {
            return null;
        }

        if (!$this->isName($methodCall->name, 'run')) {
            return null;
        }

        if (!$this->isObjectType($methodCall->var, new ObjectType('SilverStripe\Dev\BuildTask')) && 
            !$this->isObjectType($methodCall->var, new ObjectType('App\Tasks\MyTask'))) {
            return null;
        }

        $varName = $this->getName($methodCall->var);
        
        // If it's an inline instantiation like (new MyTask())->run(), we can't easily track setters,
        // but we still need to migrate the run() signature.
        $params = $varName !== null ? $this->collectAndRemoveSetters($node, $varName) : [];

        $definitionVar = new Variable('definition');
        $inputVar = new Variable('input');
        $outputVar = new Variable('output');

        $nodes = [];

        // $definition = new InputDefinition($task->getOptions());
        $nodes[] = new Expression(new Assign($definitionVar, new New_(
            new Node\Name\FullyQualified('Symfony\Component\Console\Input\InputDefinition'),
            [new Node\Arg(new MethodCall($methodCall->var, 'getOptions'))]
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
        $methodCall->args = [
            new Node\Arg($inputVar),
            new Node\Arg($outputVar)
        ];
        $nodes[] = $node;

        return $nodes;
    }

    private function collectAndRemoveSetters(Node $currentNode, string $varName): array
    {
        $params = [];
        $parent = $currentNode->getAttribute('parent');
        
        if (!$parent instanceof Node\Stmt\ClassMethod && !$parent instanceof Node\Stmt\Function_ && !$parent instanceof Node\File) {
            return [];
        }

        $stmts = $parent instanceof Node\File ? $parent->stmts : $parent->stmts;
        if ($stmts === null) return [];

        foreach ($stmts as $stmt) {
            if ($stmt === $currentNode) {
                break;
            }

            if ($stmt instanceof Expression) {
                // Reset params if the variable is reassigned to avoid bleeding between instances
                if ($stmt->expr instanceof Assign && $stmt->expr->var instanceof Variable && $this->isName($stmt->expr->var, $varName)) {
                    $params = [];
                    continue;
                }

                if ($stmt->expr instanceof MethodCall) {
                    $call = $stmt->expr;
                    if ($this->isName($call->var, $varName) && str_starts_with($this->getName($call->name) ?? '', 'set')) {
                        $key = str_replace('set', '', $this->getName($call->name));
                        $args = $call->getArgs();
                        if (isset($args[0])) {
                            $params[] = new ArrayItem($args[0]->value, new String_($key));
                            $this->removeNode($stmt);
                        }
                    }
                }
            }
        }

        return $params;
    }
}
