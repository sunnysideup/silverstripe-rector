<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\Visibility\Fixtures;

use LeftAndMain;
use EditFormFactory;

class LeftAndMain {
    protected function jsonError() {}
}

class EditFormFactory {
    public function namespaceFields() {}
}

class CustomAdmin extends LeftAndMain {
    // 4. overriding class method in subclass (should annotate)
    protected function jsonError() {}
}

class CustomFactory extends EditFormFactory {
    // 7. visibility change parent protected -> public with stricter child override (should annotate override)
    protected function namespaceFields() {}
}

class Demo {
    public function execute(LeftAndMain $admin, EditFormFactory $factory, $unknownObject) {
        
        // 1 & 8. known typed method call (public to protected external call - should annotate)
        $admin->jsonError();

        // 2. unknown typed method call + fallback safe = true (should annotate)
        $unknownObject->namespaceFields();

        // 3. unknown typed method call + fallback safe = false (should NOT annotate)
        $unknownObject->jsonError();

        // 5. already annotated code (should not duplicate)
        /** @TODO SSU RECTOR UPGRADE TASK - LeftAndMain::jsonError: Changed visibility for method LeftAndMain::jsonError() from public to protected */
        $admin->jsonError();

        // 6. class method with existing docblock (should append TODO, not replace)
        /**
         * Existing useful comment.
         */
        $factory->namespaceFields();
    }
}
-----
<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\Visibility\Fixtures;

use LeftAndMain;
use EditFormFactory;

class LeftAndMain {
    protected function jsonError() {}
}

class EditFormFactory {
    public function namespaceFields() {}
}

class CustomAdmin extends LeftAndMain {
    // 4. overriding class method in subclass (should annotate)
    /** @TODO SSU RECTOR UPGRADE TASK - LeftAndMain::jsonError: Changed visibility for method LeftAndMain::jsonError() from public to protected */
    protected function jsonError() {}
}

class CustomFactory extends EditFormFactory {
    // 7. visibility change parent protected -> public with stricter child override (should annotate override)
    /** @TODO SSU RECTOR UPGRADE TASK - EditFormFactory::namespaceFields: Changed visibility for method EditFormFactory::namespaceFields() from protected to public */
    protected function namespaceFields() {}
}

class Demo {
    public function execute(LeftAndMain $admin, EditFormFactory $factory, $unknownObject) {
        
        // 1 & 8. known typed method call (public to protected external call - should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - LeftAndMain::jsonError: Changed visibility for method LeftAndMain::jsonError() from public to protected */
        $admin->jsonError();

        // 2. unknown typed method call + fallback safe = true (should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - EditFormFactory::namespaceFields: Changed visibility for method EditFormFactory::namespaceFields() from protected to public */
        $unknownObject->namespaceFields();

        // 3. unknown typed method call + fallback safe = false (should NOT annotate)
        $unknownObject->jsonError();

        // 5. already annotated code (should not duplicate)
        /** @TODO SSU RECTOR UPGRADE TASK - LeftAndMain::jsonError: Changed visibility for method LeftAndMain::jsonError() from public to protected */
        $admin->jsonError();

        // 6. class method with existing docblock (should append TODO, not replace)
        /**
         * Existing useful comment.
         * @TODO SSU RECTOR UPGRADE TASK - EditFormFactory::namespaceFields: Changed visibility for method EditFormFactory::namespaceFields() from protected to public
         */
        $factory->namespaceFields();
    }
}