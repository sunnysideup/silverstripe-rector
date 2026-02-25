<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\ChangedDefaultParameterValue\Fixture;

class LeftAndMain {
    public function jsonSuccess($data = []) {}
}

class SSViewer {
    public function process($template, $arguments = null) {}
}

class CustomAdmin extends LeftAndMain {
    // 4. overriding class method in subclass (should annotate)
    public function jsonSuccess($data = []) {}
}

class Demo {
    public function run(LeftAndMain $admin, SSViewer $viewer, $unknownObject) {
        
        // 1. known typed method call (should annotate)
        $admin->jsonSuccess();

        // 2. unknown typed method call + fallback safe = true (should annotate)
        $unknownObject->process('MyTemplate');

        // 3. unknown typed method call + fallback safe = false (should NOT annotate)
        $unknownObject->jsonSuccess();

        // 5. already annotated code (should not duplicate)
        /** @TODO SSU RECTOR UPGRADE TASK - LeftAndMain::jsonSuccess: Changed default value for parameter $data in LeftAndMain::jsonSuccess() from [] to null */
        $admin->jsonSuccess(['test']);

        // 6. class method with existing docblock (should append TODO, not replace)
        /**
         * Parses the template
         */
        $viewer->process('Tpl');
    }
}
-----
<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\ChangedDefaultParameterValue\Fixture;

class LeftAndMain {
    public function jsonSuccess($data = []) {}
}

class SSViewer {
    public function process($template, $arguments = null) {}
}

class CustomAdmin extends LeftAndMain {
    // 4. overriding class method in subclass (should annotate)
    /** @TODO SSU RECTOR UPGRADE TASK - LeftAndMain::jsonSuccess: Changed default value for parameter $data in LeftAndMain::jsonSuccess() from [] to null */
    public function jsonSuccess($data = []) {}
}

class Demo {
    public function run(LeftAndMain $admin, SSViewer $viewer, $unknownObject) {
        
        // 1. known typed method call (should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - LeftAndMain::jsonSuccess: Changed default value for parameter $data in LeftAndMain::jsonSuccess() from [] to null */
        $admin->jsonSuccess();

        // 2. unknown typed method call + fallback safe = true (should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - SSViewer::process: Changed default value for parameter $arguments in SSViewer::process() from null to [] */
        $unknownObject->process('MyTemplate');

        // 3. unknown typed method call + fallback safe = false (should NOT annotate)
        $unknownObject->jsonSuccess();

        // 5. already annotated code (should not duplicate)
        /** @TODO SSU RECTOR UPGRADE TASK - LeftAndMain::jsonSuccess: Changed default value for parameter $data in LeftAndMain::jsonSuccess() from [] to null */
        $admin->jsonSuccess(['test']);

        // 6. class method with existing docblock (should append TODO, not replace)
        /**
         * Parses the template
         * @TODO SSU RECTOR UPGRADE TASK - SSViewer::process: Changed default value for parameter $arguments in SSViewer::process() from null to []
         */
        $viewer->process('Tpl');
    }
}