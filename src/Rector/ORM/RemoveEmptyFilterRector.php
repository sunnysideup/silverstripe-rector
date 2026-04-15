<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\ORM;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Netwerkstatt\SilverstripeRector\Tests\ORM\RemoveEmptyFilterRector\RemoveEmptyFilterRectorTest
 */
final class RemoveEmptyFilterRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove empty filter() calls from DataList', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use SilverStripe\CMS\Model\SiteTree;
$pages = SiteTree::get()->filter('');
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use SilverStripe\CMS\Model\SiteTree;
$pages = SiteTree::get();
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isName($node->name, 'filter')) {
            return null;
        }

        if (!$this->isObjectType($node->var, new ObjectType('SilverStripe\ORM\DataList'))) {
            return null;
        }

        $args = $node->getArgs();
        if (count($args) !== 1) {
            return null;
        }

        $firstArgValue = $args[0]->value;
        if (!$firstArgValue instanceof String_ || $firstArgValue->value !== '') {
            return null;
        }

        return $node->var;
    }
}
