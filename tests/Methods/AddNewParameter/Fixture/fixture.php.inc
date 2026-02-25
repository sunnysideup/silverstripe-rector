<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\AddNewParameter\Fixture;

class BuildTask {
    public function run($request) {}
}

class VirtualPage {
    public function castingHelper($field) {}
}

class MyTask extends BuildTask {
    // 4. Overriding class method in subclass (should annotate)
    public function run($request) {}
}

class Demo {
    public function doThings(BuildTask $task, VirtualPage $page, $unknownObject) {
        
        // 1. Known typed method call (should annotate)
        $task->run('req');

        // 2. Unknown typed method call + fallback safe = true (should annotate)
        $unknownObject->castingHelper('field');

        // 3. Unknown typed method call + fallback safe = false (should NOT annotate)
        $unknownObject->run('req');

        // 5. Already annotated code (should not duplicate)
        /** @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Added new parameter $output in BuildTask::run() */
        $task->run('req2');

        // 6. Class method with existing docblock (should append TODO)
        /**
         * Existing useful comment.
         */
        $task->run('req3');
    }
}
-----
<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\AddNewParameter\Fixture;

class BuildTask {
    /** @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Added new parameter $output in BuildTask::run() */
    public function run($request) {}
}

class VirtualPage {
    /** @TODO SSU RECTOR UPGRADE TASK - VirtualPage::castingHelper: Added new parameter $useFallback in VirtualPage::castingHelper() */
    public function castingHelper($field) {}
}

class MyTask extends BuildTask {
    // 4. Overriding class method in subclass (should annotate)
    /** @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Added new parameter $output in BuildTask::run() */
    public function run($request) {}
}

class Demo {
    public function doThings(BuildTask $task, VirtualPage $page, $unknownObject) {
        
        // 1. Known typed method call (should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Added new parameter $output in BuildTask::run() */
        $task->run('req');

        // 2. Unknown typed method call + fallback safe = true (should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - VirtualPage::castingHelper: Added new parameter $useFallback in VirtualPage::castingHelper() */
        $unknownObject->castingHelper('field');

        // 3. Unknown typed method call + fallback safe = false (should NOT annotate)
        $unknownObject->run('req');

        // 5. Already annotated code (should not duplicate)
        /** @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Added new parameter $output in BuildTask::run() */
        $task->run('req2');

        // 6. Class method with existing docblock (should append TODO)
        /**
         * Existing useful comment.
         * @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Added new parameter $output in BuildTask::run()
         */
        $task->run('req3');
    }
}