<?php

namespace App\Tasks {
    use SilverStripe\Dev\BuildTask;
    class MyTask extends BuildTask {
        public static function create(): static { return new static(); }
        public function run($request) {}
        public function setMyParam($val) {}
        public function setAnother($val) {}
        public function doSomethingElse() {}
        public function getOptions() { return []; }
    }
}

namespace {
    use App\Tasks\MyTask;
    use SilverStripe\Core\Injector\Injector;

    // 1. Standard
    function test() {
        $task = new MyTask();
        $task->setMyParam(true);
        $task->run(null);
    }

    // 2. Multiple Params
    function testMultipleParams() {
        $task = MyTask::create();
        $task->setMyParam('foo');
        $task->setAnother(123);
        $task->run(null);
    }

    // 3. No Params
    function testNoParams() {
        $task = new MyTask();
        $task->run(null);
    }

    // 4. Different Variable
    function testDifferentVariable() {
        $t = new MyTask();
        $t->setMyParam(false);
        $t->run(null);
    }

    // 5. Mixed Scope (Echo in middle)
    function testMixedScope() {
        $task = new MyTask();
        $task->setMyParam(true);
        echo "middle";
        $task->run(null);
    }

    // 6. Expression in Method
    function testExpressionInMethod() {
        $task = MyTask::create();
        $task->setMyParam(filter_var('true', FILTER_VALIDATE_BOOLEAN));
        $task->run(null);
    }

    // 7. Re-assignment (Prevents bleeding params)
    function testReassignment() {
        $task = new MyTask();
        $task->setMyParam('first');
        $task->run(null);

        $task = new MyTask();
        $task->setAnother('second');
        $task->run(null);
    }

    // 8. Ignoring unrelated methods
    function testUnrelatedMethods() {
        $task = new MyTask();
        $task->doSomethingElse();
        $task->setMyParam('kept');
        $task->run(null);
    }

    // 9. Passing variables to setters
    function testVariableArguments() {
        $task = new MyTask();
        $myVar = 'dynamic';
        $task->setMyParam($myVar);
        $task->run(null);
    }

    // 10. Task passed as method argument
    function testTaskAsArgument(MyTask $injectedTask) {
        $injectedTask->setMyParam('injected');
        $injectedTask->run(null);
    }

    // 11. Property assignment (Should be ignored but not crash)
    function testPropertyAssignment() {
        $task = new MyTask();
        $task->MyParam = true;
        $task->run(null);
    }

    // 12. Complex method call in setter
    function testMethodCallArgument() {
        $task = new MyTask();
        $task->setMyParam(Injector::inst()->get('SomeService')->getValue());
        $task->run(null);
    }

    // 13. Inline Instantiation (No variable tracking possible, but run should upgrade)
    function testInlineInstantiation() {
        (new MyTask())->run(null);
    }

    // 14. Idempotency check - Should not run twice
    function testAlreadyMigrated() {
        $task = new MyTask();
        $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
        $input = new \Symfony\Component\Console\Input\ArrayInput(['MyParam' => true], $definition);
        $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
        $task->run($input, $output);
    }

    // 15. Inline Idempotency check
    function testInlineAlreadyMigrated() {
        $definition = new \Symfony\Component\Console\Input\InputDefinition((new MyTask())->getOptions());
        $input = new \Symfony\Component\Console\Input\ArrayInput([], $definition);
        $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
        (new MyTask())->run($input, $output);
    }
}
-----
<?php

namespace App\Tasks {
    use SilverStripe\Dev\BuildTask;
    class MyTask extends BuildTask {
        public static function create(): static { return new static(); }
        public function run($request) {}
        public function setMyParam($val) {}
        public function setAnother($val) {}
        public function doSomethingElse() {}
        public function getOptions() { return []; }
    }
}

namespace {
    use App\Tasks\MyTask;
    use SilverStripe\Core\Injector\Injector;

    // 1. Standard
    function test() {
        $task = new MyTask();
        $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
        $input = new \Symfony\Component\Console\Input\ArrayInput(['MyParam' => true], $definition);
        $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
        $task->run($input, $output);
    }

    // 2. Multiple Params
    function testMultipleParams() {
        $task = MyTask::create();
        $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
        $input = new \Symfony\Component\Console\Input\ArrayInput(['MyParam' => 'foo', 'Another' => 123], $definition);
        $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
        $task->run($input, $output);
    }

    // 3. No Params
    function testNoParams() {
        $task = new MyTask();
        $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
        $input = new \Symfony\Component\Console\Input\ArrayInput([], $definition);
        $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
        $task->run($input, $output);
    }

    // 4. Different Variable
    function testDifferentVariable() {
        $t = new MyTask();
        $definition = new \Symfony\Component\Console\Input\InputDefinition($t->getOptions());
        $input = new \Symfony\Component\Console\Input\ArrayInput(['MyParam' => false], $definition);
        $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
        $t->run($input, $output);
    }

    // 5. Mixed Scope (Echo in middle)
    function testMixedScope() {
        $task = new MyTask();
        echo "middle";
        $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
        $input = new \Symfony\Component\Console\Input\ArrayInput(['MyParam' => true], $definition);
        $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
        $task->run($input, $output);
    }

    // 6. Expression in Method
    function testExpressionInMethod() {
        $task = MyTask::create();
        $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
        $input = new \Symfony\Component\Console\Input\ArrayInput(['MyParam' => filter_var('true', FILTER_VALIDATE_BOOLEAN)], $definition);
        $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
        $task->run($input, $output);
    }

    // 7. Re-assignment (Prevents bleeding params)
    function testReassignment() {
        $task = new MyTask();
        $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
        $input = new \Symfony\Component\Console\Input\ArrayInput(['MyParam' => 'first'], $definition);
        $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
        $task->run($input, $output);

        $task = new MyTask();
        $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
        $input = new \Symfony\Component\Console\Input\ArrayInput(['Another' => 'second'], $definition);
        $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
        $task->run($input, $output);
    }

    // 8. Ignoring unrelated methods
    function testUnrelatedMethods() {
        $task = new MyTask();
        $task->doSomethingElse();
        $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
        $input = new \Symfony\Component\Console\Input\ArrayInput(['MyParam' => 'kept'], $definition);
        $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
        $task->run($input, $output);
    }

    // 9. Passing variables to setters
    function testVariableArguments() {
        $task = new MyTask();
        $myVar = 'dynamic';
        $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
        $input = new \Symfony\Component\Console\Input\ArrayInput(['MyParam' => $myVar], $definition);
        $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
        $task->run($input, $output);
    }

    // 10. Task passed as method argument
    function testTaskAsArgument(MyTask $injectedTask) {
        $definition = new \Symfony\Component\Console\Input\InputDefinition($injectedTask->getOptions());
        $input = new \Symfony\Component\Console\Input\ArrayInput(['MyParam' => 'injected'], $definition);
        $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
        $injectedTask->run($input, $output);
    }

    // 11. Property assignment (Should be ignored but not crash)
    function testPropertyAssignment() {
        $task = new MyTask();
        $task->MyParam = true;
        $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
        $input = new \Symfony\Component\Console\Input\ArrayInput([], $definition);
        $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
        $task->run($input, $output);
    }

    // 12. Complex method call in setter
    function testMethodCallArgument() {
        $task = new MyTask();
        $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
        $input = new \Symfony\Component\Console\Input\ArrayInput(['MyParam' => Injector::inst()->get('SomeService')->getValue()], $definition);
        $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
        $task->run($input, $output);
    }

    // 13. Inline Instantiation (No variable tracking possible, but run should upgrade)
    function testInlineInstantiation() {
        $definition = new \Symfony\Component\Console\Input\InputDefinition((new MyTask())->getOptions());
        $input = new \Symfony\Component\Console\Input\ArrayInput([], $definition);
        $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
        (new MyTask())->run($input, $output);
    }

    // 14. Idempotency check - Should not run twice
    function testAlreadyMigrated() {
        $task = new MyTask();
        $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
        $input = new \Symfony\Component\Console\Input\ArrayInput(['MyParam' => true], $definition);
        $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
        $task->run($input, $output);
    }

    // 15. Inline Idempotency check
    function testInlineAlreadyMigrated() {
        $definition = new \Symfony\Component\Console\Input\InputDefinition((new MyTask())->getOptions());
        $input = new \Symfony\Component\Console\Input\ArrayInput([], $definition);
        $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
        (new MyTask())->run($input, $output);
    }
}
