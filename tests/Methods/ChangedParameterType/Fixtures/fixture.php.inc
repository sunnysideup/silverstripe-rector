<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\ChangedParameterType\Fixtures;

class BuildTask {
    public function run($request) {}
}

class LeftAndMain {
    public function jsonError($errorCode) {}
}

class CustomTask extends BuildTask {
    // 4. overriding class method in subclass (should annotate)
    public function run($request) {}
}

class Demo {
    public function execute(BuildTask $task, LeftAndMain $admin, $unknownObject) {
        
        // 1. known typed method call (should annotate)
        $task->run('req');

        // 2. unknown typed method call + fallback safe = true (should annotate)
        $unknownObject->jsonError(404);

        // 3. unknown typed method call + fallback safe = false (should NOT annotate)
        $unknownObject->run('req');

        // 5. already annotated code (should not duplicate)
        /** @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Changed type of parameter $request in BuildTask::run() from dynamic to Symfony\Component\Console\Input\InputInterface */
        $task->run('req2');

        // 6. class method with existing docblock (should append TODO, not replace)
        /**
         * Existing useful comment.
         */
        $task->run('req3');
    }
}
-----
<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\ChangedParameterType\Fixtures;

class BuildTask {
    public function run($request) {}
}

class LeftAndMain {
    public function jsonError($errorCode) {}
}

class CustomTask extends BuildTask {
    // 4. overriding class method in subclass (should annotate)
    /** @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Changed type of parameter $request in BuildTask::run() from dynamic to Symfony\Component\Console\Input\InputInterface */
    public function run($request) {}
}

class Demo {
    public function execute(BuildTask $task, LeftAndMain $admin, $unknownObject) {
        
        // 1. known typed method call (should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Changed type of parameter $request in BuildTask::run() from dynamic to Symfony\Component\Console\Input\InputInterface */
        $task->run('req');

        // 2. unknown typed method call + fallback safe = true (should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - LeftAndMain::jsonError: Changed type of parameter $errorCode in LeftAndMain::jsonError() from dynamic to int */
        $unknownObject->jsonError(404);

        // 3. unknown typed method call + fallback safe = false (should NOT annotate)
        $unknownObject->run('req');

        // 5. already annotated code (should not duplicate)
        /** @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Changed type of parameter $request in BuildTask::run() from dynamic to Symfony\Component\Console\Input\InputInterface */
        $task->run('req2');

        // 6. class method with existing docblock (should append TODO, not replace)
        /**
         * Existing useful comment.
         * @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Changed type of parameter $request in BuildTask::run() from dynamic to Symfony\Component\Console\Input\InputInterface
         */
        $task->run('req3');
    }
}