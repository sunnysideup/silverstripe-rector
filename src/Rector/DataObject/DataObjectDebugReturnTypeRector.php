<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\DataObject;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Scalar\String_;
use PhpParser\Modifiers;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Netwerkstatt\SilverstripeRector\Tests\DataObject\DataObjectDebugReturnTypeRector\DataObjectDebugReturnTypeRectorTest
 */
final class DataObjectDebugReturnTypeRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Changes DataObject debug() method to public with string return type', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class MyObject extends \SilverStripe\ORM\DataObject {
    protected function debug() {
        return "Debug info";
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class MyObject extends \SilverStripe\ORM\DataObject {
    public function debug(): string {
        return "Debug info";
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
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isObjectType($node, new ObjectType('SilverStripe\ORM\DataObject'))) {
            return null;
        }

        $hasChanged = false;

        foreach ($node->getMethods() as $method) {
            if (!$this->isName($method->name, 'debug')) {
                continue;
            }

            // 1. Ensure it is public (remove protected/private if present)
            if (!$method->isPublic()) {
                $method->flags = $method->flags & ~Modifiers::PROTECTED & ~Modifiers::PRIVATE;
                $method->flags |= Modifiers::PUBLIC;
                $hasChanged = true;
            }

            // 2. Set Return Type to string
            if ($method->returnType === null || !$this->isName($method->returnType, 'string')) {
                $method->returnType = new Identifier('string');
                $hasChanged = true;
            }

            // 3. Prevent fatal errors if there's no return statement
            if ($method->stmts !== null && $hasChanged) {
                $hasReturn = false;
                foreach ($method->stmts as $stmt) {
                    if ($stmt instanceof Return_) {
                        $hasReturn = true;
                        break;
                    }
                }

                if (!$hasReturn) {
                    $method->stmts[] = new Return_(new String_(''));
                }
            }
        }

        return $hasChanged ? $node : null;
    }
}
