<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\Console;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ConvertArrayToInputOptionRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Convert array option definitions to InputOption objects in getOptions()',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
public function getOptions(): array
{
    return [
        ['limit', 'l', InputOption::VALUE_OPTIONAL, 'Limit number of examples']
    ];
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
public function getOptions(): array
{
    return [
        new \Symfony\Component\Console\Input\InputOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'Limit number of examples')
    ];
}
CODE_SAMPLE
                )
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node, 'getOptions') || $node->stmts === null) {
            return null;
        }

        $hasChanged = false;

        $this->traverseNodesWithCallable($node->stmts, function (Node $subNode) use (&$hasChanged) {
            if (! $subNode instanceof Array_) {
                return null;
            }

            $arrayModified = false;

            foreach ($subNode->items as $item) {
                if (! $item instanceof ArrayItem) {
                    continue;
                }

                if (! $item->value instanceof Array_) {
                    continue;
                }

                $innerArray = $item->value;

                if ($this->isInputOptionDefinition($innerArray)) {
                    $item->value = $this->createInputOptionNode($innerArray);
                    $arrayModified = true;
                    $hasChanged = true;
                }
            }

            return $arrayModified ? $subNode : null;
        });

        return $hasChanged ? $node : null;
    }

    private function isInputOptionDefinition(Array_ $array): bool
    {
        if (count($array->items) < 1) {
            return false;
        }

        $firstItem = $array->items[0]?->value;
        if (! $firstItem instanceof String_) {
            return false;
        }

        // Ensure no nested arrays inside the definition to avoid false positives
        foreach ($array->items as $item) {
            if ($item?->value instanceof Array_) {
                return false;
            }
        }

        return true;
    }

    private function createInputOptionNode(Array_ $array): New_
    {
        $args = [];
        foreach ($array->items as $item) {
            if ($item instanceof ArrayItem) {
                $args[] = new Arg($item->value);
            }
        }

        return new New_(
            new FullyQualified('Symfony\Component\Console\Input\InputOption'),
            $args
        );
    }
}
