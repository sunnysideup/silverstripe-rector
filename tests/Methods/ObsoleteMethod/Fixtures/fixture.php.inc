<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\ObsoleteMethod\Fixture;

use DNADesign\Elemental\Controllers\ElementalAreaController;
use SilverStripe\Admin\LeftAndMain;

class ElementalAreaController {
    public function formAction() {}
}

class LeftAndMain {
    public function getSearchFilter() {}
}

class CustomController extends ElementalAreaController {
    // 4. overriding class method in subclass (should annotate)
    public function formAction() {}
}

class Demo {
    public function execute(ElementalAreaController $controller, LeftAndMain $admin, $unknownObject) {
        
        // 1. known typed method call (should annotate)
        $controller->formAction();

        // 2. unknown typed method call + fallback safe = true (should annotate)
        $unknownObject->getSearchFilter();

        // 3. unknown typed method call + fallback safe = false (should NOT annotate)
        $unknownObject->formAction();

        // 5. already annotated code (should not duplicate)
        /** @TODO SSU RECTOR UPGRADE TASK - DNADesign\Elemental\Controllers\ElementalAreaController::formAction: removed without equivalent functionality to replace it */
        $controller->formAction();

        // 6. class method with existing docblock (should append TODO, not replace)
        /**
         * Existing useful comment.
         */
        $controller->formAction();
    }
}
-----
<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\ObsoleteMethod\Fixture;

use DNADesign\Elemental\Controllers\ElementalAreaController;
use SilverStripe\Admin\LeftAndMain;

class ElementalAreaController {
    public function formAction() {}
}

class LeftAndMain {
    public function getSearchFilter() {}
}

class CustomController extends ElementalAreaController {
    // 4. overriding class method in subclass (should annotate)
    /** @TODO SSU RECTOR UPGRADE TASK - DNADesign\Elemental\Controllers\ElementalAreaController::formAction: removed without equivalent functionality to replace it */
    public function formAction() {}
}

class Demo {
    public function execute(ElementalAreaController $controller, LeftAndMain $admin, $unknownObject) {
        
        // 1. known typed method call (should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - DNADesign\Elemental\Controllers\ElementalAreaController::formAction: removed without equivalent functionality to replace it */
        $controller->formAction();

        // 2. unknown typed method call + fallback safe = true (should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - SilverStripe\Admin\LeftAndMain::getSearchFilter: removed without equivalent functionality to replace it */
        $unknownObject->getSearchFilter();

        // 3. unknown typed method call + fallback safe = false (should NOT annotate)
        $unknownObject->formAction();

        // 5. already annotated code (should not duplicate)
        /** @TODO SSU RECTOR UPGRADE TASK - DNADesign\Elemental\Controllers\ElementalAreaController::formAction: removed without equivalent functionality to replace it */
        $controller->formAction();

        // 6. class method with existing docblock (should append TODO, not replace)
        /**
         * Existing useful comment.
         * @TODO SSU RECTOR UPGRADE TASK - DNADesign\Elemental\Controllers\ElementalAreaController::formAction: removed without equivalent functionality to replace it
         */
        $controller->formAction();
    }
}