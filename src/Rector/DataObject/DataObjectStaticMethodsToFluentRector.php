<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\DataObject;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Arg;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Netwerkstatt\SilverstripeRector\Tests\DataObject\DataObjectStaticMethodsToFluentRector\DataObjectStaticMethodsToFluentRectorTest
 */
final class DataObjectStaticMethodsToFluentRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Silverstripe 6.1: Replace DataObject static methods with fluent equivalents using Late Static Binding.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
DataObject::get_one(Member::class, ['Email' => $email], false, 'ID DESC');
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
Member::get()->setUseCache(false)->filter(['Email' => $email])->sort('ID DESC')->first();
CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isObjectType($node->class, new ObjectType('SilverStripe\ORM\DataObject'))) {
            return null;
        }

        if ($this->isName($node->name, 'get_by_id')) {
            return $this->refactorGetById($node);
        }

        if ($this->isName($node->name, 'get_one')) {
            return $this->refactorGetOne($node);
        }

        if ($this->isName($node->name, 'delete_by_id')) {
            return $this->refactorDeleteById($node);
        }

        return null;
    }

    private function refactorGetById(StaticCall $node): ?Node
    {
        if (count($node->args) < 2) {
            return null;
        }

        $classNameArg = $node->args[0]->value;
        $idArg = $node->args[1];

        $getCall = new StaticCall($classNameArg, 'get');
        $setUseCacheCall = $this->nodeFactory->createMethodCall($getCall, 'setUseCache', [new Arg($this->nodeFactory->createTrue())]);

        return $this->nodeFactory->createMethodCall($setUseCacheCall, 'byID', [$idArg]);
    }

    private function refactorGetOne(StaticCall $node): ?Node
    {
        if (count($node->args) < 1) {
            return null;
        }

        $classNameArg = $node->args[0]->value;
        $filterArg = $node->args[1] ?? null;
        $cacheArg = $node->args[2] ?? null;
        $sortArg = $node->args[3] ?? null;

        $currentCall = new StaticCall($classNameArg, 'get');

        // 1. Cache handling (Defaults to true)
        $cacheValue = $cacheArg ? $cacheArg->value : $this->nodeFactory->createTrue();
        $currentCall = $this->nodeFactory->createMethodCall($currentCall, 'setUseCache', [new Arg($cacheValue)]);

        // 2. Filter handling
        if ($filterArg && !$this->valueResolver->isNull($filterArg->value)) {
            $currentCall = $this->nodeFactory->createMethodCall($currentCall, 'filter', [$filterArg]);
        }

        // 3. Sort handling
        if ($sortArg && !$this->valueResolver->isNull($sortArg->value)) {
            $currentCall = $this->nodeFactory->createMethodCall($currentCall, 'sort', [$sortArg]);
        }

        return $this->nodeFactory->createMethodCall($currentCall, 'first');
    }

    private function refactorDeleteById(StaticCall $node): ?Node
    {
        if (count($node->args) < 2) {
            return null;
        }

        $classNameArg = $node->args[0]->value;
        $idArg = $node->args[1];

        $getCall = new StaticCall($classNameArg, 'get');
        $setUseCacheCall = $this->nodeFactory->createMethodCall($getCall, 'setUseCache', [new Arg($this->nodeFactory->createTrue())]);
        $byIDCall = $this->nodeFactory->createMethodCall($setUseCacheCall, 'byID', [$idArg]);

        return $this->nodeFactory->createMethodCall($byIDCall, 'delete');
    }
}
