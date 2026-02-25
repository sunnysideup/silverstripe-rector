<?php

// 1. Declare the mock in the exact namespace the config is looking for
namespace DNADesign\Elemental\Controllers {
    class ElementalAreaController {
        public function formAction() {}
    }
}

// 2. Run the test code in your test namespace
namespace Netwerkstatt\SilverstripeRector\Tests\Methods\ObsoleteMethod\Fixtures {

    use DNADesign\Elemental\Controllers\ElementalAreaController;

    class CustomController extends ElementalAreaController {
        // 4. overriding class method in subclass (should annotate)
        public function formAction() {}
    }

    class Demo {
        public function execute(ElementalAreaController $controller, $unknownObject) {
            
            // 1. known typed method call (should annotate)
            $controller->formAction();

            // 2. unknown typed method call + fallback safe = true (should annotate - assuming config allows)
            // (Skipped for this specific test unless you added a true fallback to the config)

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
}
-----
<?php

// 1. Declare the mock in the exact namespace the config is looking for
namespace DNADesign\Elemental\Controllers {
    class ElementalAreaController {
        /** @TODO SSU RECTOR UPGRADE TASK - DNADesign\Elemental\Controllers\ElementalAreaController::formAction: removed without equivalent functionality to replace it */
        public function formAction() {}
    }
}

// 2. Run the test code in your test namespace
namespace Netwerkstatt\SilverstripeRector\Tests\Methods\ObsoleteMethod\Fixtures {

    use DNADesign\Elemental\Controllers\ElementalAreaController;

    class CustomController extends ElementalAreaController {
        // 4. overriding class method in subclass (should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - DNADesign\Elemental\Controllers\ElementalAreaController::formAction: removed without equivalent functionality to replace it */
        public function formAction() {}
    }

    class Demo {
        public function execute(ElementalAreaController $controller, $unknownObject) {
            
            // 1. known typed method call (should annotate)
            /** @TODO SSU RECTOR UPGRADE TASK - DNADesign\Elemental\Controllers\ElementalAreaController::formAction: removed without equivalent functionality to replace it */
            $controller->formAction();

            // 2. unknown typed method call + fallback safe = true (should annotate - assuming config allows)
            // (Skipped for this specific test unless you added a true fallback to the config)

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
}