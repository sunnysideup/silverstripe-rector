<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\ReturnType\Fixtures;

class BaseElement {
    public function forTemplate() {}
}

class LeftAndMain {
    public function getRecord() {}
}

class CustomElement extends BaseElement {
    // 4. overriding class method in subclass (should annotate)
    public function forTemplate() {}
}

class Demo {
    public function execute(BaseElement $element, LeftAndMain $admin, $unknownObject) {
        
        // 1. known typed method call (should annotate)
        $element->forTemplate();

        // 2. unknown typed method call + fallback safe = true (should annotate)
        $unknownObject->getRecord();

        // 3. unknown typed method call + fallback safe = false (should NOT annotate)
        $unknownObject->forTemplate();

        // 5. already annotated code (should not duplicate comment)
        /** @TODO SSU RECTOR UPGRADE TASK - BaseElement::forTemplate: Changed return type for method BaseElement::forTemplate() from dynamic to string */
        $element->forTemplate();

        // 6. class method with existing docblock (should append TODO)
        /**
         * Existing useful comment.
         */
        $element->forTemplate();
    }
}
-----
<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\ReturnType\Fixtures;

class BaseElement {
    public function forTemplate() {}
}

class LeftAndMain {
    public function getRecord() {}
}

class CustomElement extends BaseElement {
    // 4. overriding class method in subclass (should annotate)
    /** @TODO SSU RECTOR UPGRADE TASK - BaseElement::forTemplate: Changed return type for method BaseElement::forTemplate() from dynamic to string */
    public function forTemplate() {}
}

class Demo {
    public function execute(BaseElement $element, LeftAndMain $admin, $unknownObject) {
        
        // 1. known typed method call (should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - BaseElement::forTemplate: Changed return type for method BaseElement::forTemplate() from dynamic to string */
        $element->forTemplate();

        // 2. unknown typed method call + fallback safe = true (should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - LeftAndMain::getRecord: Changed return type for method LeftAndMain::getRecord() from dynamic to DataObject|null */
        $unknownObject->getRecord();

        // 3. unknown typed method call + fallback safe = false (should NOT annotate)
        $unknownObject->forTemplate();

        // 5. already annotated code (should not duplicate comment)
        /** @TODO SSU RECTOR UPGRADE TASK - BaseElement::forTemplate: Changed return type for method BaseElement::forTemplate() from dynamic to string */
        $element->forTemplate();

        // 6. class method with existing docblock (should append TODO)
        /**
         * Existing useful comment.
         * @TODO SSU RECTOR UPGRADE TASK - BaseElement::forTemplate: Changed return type for method BaseElement::forTemplate() from dynamic to string
         */
        $element->forTemplate();
    }
}