<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\RenamedParameter\Fixture;

class BuildTask {
    public function run($request) {}
}

class VirtualPage {
    public function hasField($field) {}
}

class CustomTask extends BuildTask {
    // 4. overriding class method in subclass (should annotate)
    public function run($request) {}
}

class Demo {
    public function execute(BuildTask $task, VirtualPage $page, $unknownObject) {
        
        // 1. known typed method call (should annotate)
        $task->run('req');

        // 2. unknown typed method call + fallback safe = true (should annotate)
        $unknownObject->hasField('Title');

        // 3. unknown typed method call + fallback safe = false (should NOT annotate)
        $unknownObject->run('req');

        // 5. already annotated code (should not duplicate)
        /** @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Renamed parameter $request in BuildTask::run() to $input */
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

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\RenamedParameter\Fixture;

class BuildTask {
    public function run($request) {}
}

class VirtualPage {
    public function hasField($field) {}
}

class CustomTask extends BuildTask {
    // 4. overriding class method in subclass (should annotate)
    /** @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Renamed parameter $request in BuildTask::run() to $input */
    public function run($request) {}
}

class Demo {
    public function execute(BuildTask $task, VirtualPage $page, $unknownObject) {
        
        // 1. known typed method call (should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Renamed parameter $request in BuildTask::run() to $input */
        $task->run('req');

        // 2. unknown typed method call + fallback safe = true (should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - VirtualPage::hasField: Renamed parameter $field in VirtualPage::hasField() to $fieldName */
        $unknownObject->hasField('Title');

        // 3. unknown typed method call + fallback safe = false (should NOT annotate)
        $unknownObject->run('req');

        // 5. already annotated code (should not duplicate)
        /** @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Renamed parameter $request in BuildTask::run() to $input */
        $task->run('req2');

        // 6. class method with existing docblock (should append TODO, not replace)
        /**
         * Existing useful comment.
         * @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Renamed parameter $request in BuildTask::run() to $input
         */
        $task->run('req3');
    }
}