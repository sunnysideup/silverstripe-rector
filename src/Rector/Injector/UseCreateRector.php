<?php

namespace Netwerkstatt\SilverstripeRector\Rector\Injector;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\PhpAttribute\NodeFactory\PhpAttributeGroupFactory;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\CodeSample\ConfiguredCodeSample;
use Rector\RuleDoc\RuleDefinition;

class UseCreateRector extends AbstractRector implements DocumentedRuleInterface
{
    public function __construct(private readonly \PHPStan\Reflection\ReflectionProvider $reflectionProvider)
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Code Style: Change new Object to static call for classes that use Injectable trait',
            [
                new CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        new InjectableClass($name);
    }
}
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        InjectableClass::create($name);
    }
}
CODE_SAMPLE
            )
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [New_::class, MethodCall::class];
    }

    /**
     * @param New_|MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof MethodCall) {
            return $this->refactorMethodCallOnNew($node);
        }

        return $this->createStaticCall($node);
    }

    /**
     * `(new Foo())->bar()`: Rector's PHP 8.4 NewMethodCallWithoutParenthesesRector blanks the opening
     * parenthesis in the old token stream. If only the inner `new` were replaced, the printer would keep the
     * closing parenthesis and produce `Foo::create())->bar()`. Returning a new MethodCall makes the printer
     * print the whole call from scratch instead.
     */
    private function refactorMethodCallOnNew(MethodCall $methodCall): ?MethodCall
    {
        if (!$methodCall->var instanceof New_) {
            return null;
        }

        $staticCall = $this->createStaticCall($methodCall->var);
        if (!$staticCall instanceof StaticCall) {
            return null;
        }

        $newMethodCall = new MethodCall($staticCall, $methodCall->name, $methodCall->args);
        $newMethodCall->setAttribute('comments', $methodCall->getComments());

        return $newMethodCall;
    }

    private function createStaticCall(New_ $new): ?StaticCall
    {
        $class = $new->class;
        if (!method_exists($class, 'toString')) {
            return null; //we have something like "new $foo"
        }

        $className = $class->toString();
        if (!$this->reflectionProvider->hasClass($className)) {
            return null;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        if (!$classReflection->hasTraitUse(\SilverStripe\Core\Injector\Injectable::class)) {
            return null;
        }

        return $this->nodeFactory->createStaticCall($new->class, 'create', $new->args);
    }
}
