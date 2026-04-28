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
    }
}
