<?php

namespace SilverStripe\Dev {
    class BuildTask {
        public function getOptions() { return []; }
        public function run($input = null, $output = null) {}
    }
}
namespace App\Tasks {
    class MyTask extends \SilverStripe\Dev\BuildTask {
        public static function create() { return new self(); }
        public function setMyParam($val) { return $this; }
        public function setOne($val) { return $this; }
        public function setTwo($val) { return $this; }
        public function doSomethingElse() { return $this; }
    }
}
namespace App\Test {
    class TaskRunner {
        // 1. Basic Variable Task with Setter
        public function testVariableWithSetter() {
            $task = new \App\Tasks\MyTask();
            $task->setMyParam(true);
            $task->run(null);
        }

        // 2. Inline Creation (StaticCall)
        public function testInlineCreation() {
            \App\Tasks\MyTask::create()->run();
        }

        // 3. Multiple Setters Aggregation
        public function testMultipleSetters() {
            $t = \App\Tasks\MyTask::create();
            $t->setOne('a');
            $t->setTwo('b');
            $t->run(null);
        }

        // 4. Idempotency Test (Should be skipped)
        public function testIdempotency() {
            $t = new \App\Tasks\MyTask();
            $input = new \Symfony\Component\Console\Input\ArrayInput([]);
            $output = \SilverStripe\PolyExecution\PolyOutput::create(1);
            $t->run($input, $output);
        }

        // 5. Task passed as method argument
        function testTaskAsArgument(\App\Tasks\MyTask $injectedTask) {
            $injectedTask->setMyParam('injected');
            $injectedTask->run(null);
        }

        // 6. Inline Instantiation
        function testInlineInstantiation() {
            (new \App\Tasks\MyTask())->run();
        }

        // 7. Variable Task with NO setters
        function testNoSetters() {
            $task = new \App\Tasks\MyTask();
            $task->run(null);
        }

        // 8. Variable assignment from StaticCall
        function testVariableFromStaticCall() {
            $task = \App\Tasks\MyTask::create();
            $task->run();
        }

        // 9. Method arguments passed to setter are variables
        function testSetterWithVariableArg() {
            $val = true;
            $task = new \App\Tasks\MyTask();
            $task->setMyParam($val);
            $task->run();
        }

        // 10. Method arguments passed to setter are method calls
        function testSetterWithMethodCallArg() {
            $task = new \App\Tasks\MyTask();
            $task->setMyParam($this->getVal());
            $task->run();
        }

        public function getVal() { return 'foo'; }

        // 11. Two completely separate tasks in the same method
        function testMultipleTasksInSameMethod() {
            $t1 = \App\Tasks\MyTask::create();
            $t1->setOne(1);
            $t1->run();

            $t2 = \App\Tasks\MyTask::create();
            $t2->setTwo(2);
            $t2->run();
        }

        // 12. Run called with NO arguments at all
        function testRunNoArgs() {
            $task = new \App\Tasks\MyTask();
            $task->run();
        }

        // 13. Nested inside an IF statement
        function testInsideIfStatement() {
            if (true) {
                $task = new \App\Tasks\MyTask();
                $task->setOne('nested');
                $task->run();
            }
        }

        // 14. Nested inside a FOREACH loop
        function testInsideLoop() {
            foreach ([1, 2] as $i) {
                $task = new \App\Tasks\MyTask();
                $task->setOne($i);
                $task->run();
            }
        }

        // 15. Task argument without setters
        function testArgumentWithoutSetters(\App\Tasks\MyTask $t) {
            $t->run();
        }

        // 16. Same variable reassigned to a new task in same scope
        function testVariableReused() {
            $t = new \App\Tasks\MyTask();
            $t->setOne(1);
            $t->run();

            $t = new \App\Tasks\MyTask();
            $t->setTwo(2);
            $t->run();
        }
    }
}
-----
<?php

namespace SilverStripe\Dev {
    class BuildTask {
        public function getOptions() { return []; }
        public function run($input = null, $output = null) {}
    }
}
namespace App\Tasks {
    class MyTask extends \SilverStripe\Dev\BuildTask {
        public static function create() { return new self(); }
        public function setMyParam($val) { return $this; }
        public function setOne($val) { return $this; }
        public function setTwo($val) { return $this; }
        public function doSomethingElse() { return $this; }
    }
}
namespace App\Test {
    class TaskRunner {
        // 1. Basic Variable Task with Setter
        public function testVariableWithSetter() {
            $task = new \App\Tasks\MyTask();
            $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
            $input = new \Symfony\Component\Console\Input\ArrayInput(['MyParam' => true], $definition);
            $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
            $task->run($input, $output);
        }

        // 2. Inline Creation (StaticCall)
        public function testInlineCreation() {
            $definition = new \Symfony\Component\Console\Input\InputDefinition(\App\Tasks\MyTask::create()->getOptions());
            $input = new \Symfony\Component\Console\Input\ArrayInput([], $definition);
            $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
            \App\Tasks\MyTask::create()->run($input, $output);
        }

        // 3. Multiple Setters Aggregation
        public function testMultipleSetters() {
            $t = \App\Tasks\MyTask::create();
            $definition = new \Symfony\Component\Console\Input\InputDefinition($t->getOptions());
            $input = new \Symfony\Component\Console\Input\ArrayInput(['One' => 'a', 'Two' => 'b'], $definition);
            $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
            $t->run($input, $output);
        }

        // 4. Idempotency Test (Should be skipped)
        public function testIdempotency() {
            $t = new \App\Tasks\MyTask();
            $input = new \Symfony\Component\Console\Input\ArrayInput([]);
            $output = \SilverStripe\PolyExecution\PolyOutput::create(1);
            $t->run($input, $output);
        }

        // 5. Task passed as method argument
        function testTaskAsArgument(\App\Tasks\MyTask $injectedTask) {
            $definition = new \Symfony\Component\Console\Input\InputDefinition($injectedTask->getOptions());
            $input = new \Symfony\Component\Console\Input\ArrayInput(['MyParam' => 'injected'], $definition);
            $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
            $injectedTask->run($input, $output);
        }

        // 6. Inline Instantiation
        function testInlineInstantiation() {
            $definition = new \Symfony\Component\Console\Input\InputDefinition((new \App\Tasks\MyTask())->getOptions());
            $input = new \Symfony\Component\Console\Input\ArrayInput([], $definition);
            $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
            (new \App\Tasks\MyTask())->run($input, $output);
        }

        // 7. Variable Task with NO setters
        function testNoSetters() {
            $task = new \App\Tasks\MyTask();
            $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
            $input = new \Symfony\Component\Console\Input\ArrayInput([], $definition);
            $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
            $task->run($input, $output);
        }

        // 8. Variable assignment from StaticCall
        function testVariableFromStaticCall() {
            $task = \App\Tasks\MyTask::create();
            $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
            $input = new \Symfony\Component\Console\Input\ArrayInput([], $definition);
            $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
            $task->run($input, $output);
        }

        // 9. Method arguments passed to setter are variables
        function testSetterWithVariableArg() {
            $val = true;
            $task = new \App\Tasks\MyTask();
            $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
            $input = new \Symfony\Component\Console\Input\ArrayInput(['MyParam' => $val], $definition);
            $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
            $task->run($input, $output);
        }

        // 10. Method arguments passed to setter are method calls
        function testSetterWithMethodCallArg() {
            $task = new \App\Tasks\MyTask();
            $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
            $input = new \Symfony\Component\Console\Input\ArrayInput(['MyParam' => $this->getVal()], $definition);
            $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
            $task->run($input, $output);
        }

        public function getVal() { return 'foo'; }

        // 11. Two completely separate tasks in the same method
        function testMultipleTasksInSameMethod() {
            $t1 = \App\Tasks\MyTask::create();
            $definition = new \Symfony\Component\Console\Input\InputDefinition($t1->getOptions());
            $input = new \Symfony\Component\Console\Input\ArrayInput(['One' => 1], $definition);
            $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
            $t1->run($input, $output);

            $t2 = \App\Tasks\MyTask::create();
            $definition = new \Symfony\Component\Console\Input\InputDefinition($t2->getOptions());
            $input = new \Symfony\Component\Console\Input\ArrayInput(['Two' => 2], $definition);
            $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
            $t2->run($input, $output);
        }

        // 12. Run called with NO arguments at all
        function testRunNoArgs() {
            $task = new \App\Tasks\MyTask();
            $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
            $input = new \Symfony\Component\Console\Input\ArrayInput([], $definition);
            $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
            $task->run($input, $output);
        }

        // 13. Nested inside an IF statement
        function testInsideIfStatement() {
            if (true) {
                $task = new \App\Tasks\MyTask();
                $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
                $input = new \Symfony\Component\Console\Input\ArrayInput(['One' => 'nested'], $definition);
                $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
                $task->run($input, $output);
            }
        }

        // 14. Nested inside a FOREACH loop
        function testInsideLoop() {
            foreach ([1, 2] as $i) {
                $task = new \App\Tasks\MyTask();
                $definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
                $input = new \Symfony\Component\Console\Input\ArrayInput(['One' => $i], $definition);
                $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
                $task->run($input, $output);
            }
        }

        // 15. Task argument without setters
        function testArgumentWithoutSetters(\App\Tasks\MyTask $t) {
            $definition = new \Symfony\Component\Console\Input\InputDefinition($t->getOptions());
            $input = new \Symfony\Component\Console\Input\ArrayInput([], $definition);
            $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
            $t->run($input, $output);
        }

        // 16. Same variable reassigned to a new task in same scope
        function testVariableReused() {
            $t = new \App\Tasks\MyTask();
            $definition = new \Symfony\Component\Console\Input\InputDefinition($t->getOptions());
            $input = new \Symfony\Component\Console\Input\ArrayInput(['One' => 1], $definition);
            $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
            $t->run($input, $output);

            $t = new \App\Tasks\MyTask();
            $definition = new \Symfony\Component\Console\Input\InputDefinition($t->getOptions());
            $input = new \Symfony\Component\Console\Input\ArrayInput(['Two' => 2], $definition);
            $output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
            $t->run($input, $output);
        }
    }
}
