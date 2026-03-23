<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\RemovedParameter\Fixtures;

use DNADesign\Elemental\Controllers\ElementalAreaController;
use SilverStripe\Forms\FormField;

class ElementalAreaController {
    public function elementForm($request = null) {}
}

class FormField {
    public function validate($validator = null) {}
}

class CustomField extends FormField {
    // 4. overriding class method in subclass (should annotate)
    public function validate($validator = null) {}
}

class Demo {
    public function execute(ElementalAreaController $controller, FormField $field, $unknownObject) {
        
        // 1. known typed method call (should annotate)
        $controller->elementForm('my-request');

        // 2. unknown typed method call + fallback safe = true (should annotate)
        $unknownObject->validate('my-validator');

        // 3. unknown typed method call + fallback safe = false (should NOT annotate)
        $unknownObject->elementForm('my-request');

        // 5. already annotated code (should not duplicate)
        /** @TODO SSU RECTOR UPGRADE TASK - DNADesign\Elemental\Controllers\ElementalAreaController::elementForm: Removed deprecated parameter $request in ElementalAreaController::elementForm() */
        $controller->elementForm('req2');

        // 6. class method with existing docblock (should append TODO, not replace)
        /**
         * Existing useful comment.
         */
        $controller->elementForm('req3');
    }
}
-----
<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\RemovedParameter\Fixtures;

use DNADesign\Elemental\Controllers\ElementalAreaController;
use SilverStripe\Forms\FormField;

class ElementalAreaController {
    public function elementForm($request = null) {}
}

class FormField {
    public function validate($validator = null) {}
}

class CustomField extends FormField {
    // 4. overriding class method in subclass (should annotate)
    /** @TODO SSU RECTOR UPGRADE TASK - SilverStripe\Forms\FormField::validate: Removed deprecated parameter $validator in FormField::validate() */
    public function validate($validator = null) {}
}

class Demo {
    public function execute(ElementalAreaController $controller, FormField $field, $unknownObject) {
        
        // 1. known typed method call (should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - DNADesign\Elemental\Controllers\ElementalAreaController::elementForm: Removed deprecated parameter $request in ElementalAreaController::elementForm() */
        $controller->elementForm('my-request');

        // 2. unknown typed method call + fallback safe = true (should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - SilverStripe\Forms\FormField::validate: Removed deprecated parameter $validator in FormField::validate() */
        $unknownObject->validate('my-validator');

        // 3. unknown typed method call + fallback safe = false (should NOT annotate)
        $unknownObject->elementForm('my-request');

        // 5. already annotated code (should not duplicate)
        /** @TODO SSU RECTOR UPGRADE TASK - DNADesign\Elemental\Controllers\ElementalAreaController::elementForm: Removed deprecated parameter $request in ElementalAreaController::elementForm() */
        $controller->elementForm('req2');

        // 6. class method with existing docblock (should append TODO, not replace)
        /**
         * Existing useful comment.
         * @TODO SSU RECTOR UPGRADE TASK - DNADesign\Elemental\Controllers\ElementalAreaController::elementForm: Removed deprecated parameter $request in ElementalAreaController::elementForm()
         */
        $controller->elementForm('req3');
    }
}