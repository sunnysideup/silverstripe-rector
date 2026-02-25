<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\UseInstead\Fixture;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\CMS\Controllers\CMSMain;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\AssetAdmin\Extensions\RemoteFileModalExtension;

class LeftAndMain {
    public function currentPage() {}
}

class CMSMain {
    public function CanOrganiseSitetree() {}
}

class ContentController {
    public function Menu() {}
}

class RemoteFileModalExtension {
    public function getRequest() {}
}

class CustomAdmin extends LeftAndMain {
    // 4 & 8. overriding class method in subclass (should annotate & auto-rename safely)
    public function currentPage() {}
}

class Demo {
    public function execute(LeftAndMain $admin, ContentController $controller, RemoteFileModalExtension $ext, $unknownObject) {
        
        // 1 & 7. known typed method call (should annotate & auto-rename safely)
        $admin->currentPage();

        // 9. non-method replacement note (annotate only, NO auto-fix)
        $controller->Menu();
        $ext->getRequest();

        // 2. unknown typed method call + fallback safe = true (should annotate & auto-rename)
        $unknownObject->CanOrganiseSitetree();

        // 3. unknown typed method call + fallback safe = false (should NOT annotate or rename)
        $unknownObject->currentPage();

        // 5. already annotated code (should rename if not already renamed, but not duplicate comment)
        /** @TODO SSU RECTOR UPGRADE TASK - LeftAndMain::currentPage: use currentRecord() instead. */
        $admin->currentPage();

        // 6. class method with existing docblock (should append TODO & auto-rename)
        /**
         * Existing useful comment.
         */
        $admin->currentPage();
    }
}
-----
<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\UseInstead\Fixture;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\CMS\Controllers\CMSMain;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\AssetAdmin\Extensions\RemoteFileModalExtension;

class LeftAndMain {
    public function currentPage() {}
}

class CMSMain {
    public function CanOrganiseSitetree() {}
}

class ContentController {
    public function Menu() {}
}

class RemoteFileModalExtension {
    public function getRequest() {}
}

class CustomAdmin extends LeftAndMain {
    // 4 & 8. overriding class method in subclass (should annotate & auto-rename safely)
    /** @TODO SSU RECTOR UPGRADE TASK - LeftAndMain::currentPage: use currentRecord() instead. */
    public function currentRecord() {}
}

class Demo {
    public function execute(LeftAndMain $admin, ContentController $controller, RemoteFileModalExtension $ext, $unknownObject) {
        
        // 1 & 7. known typed method call (should annotate & auto-rename safely)
        /** @TODO SSU RECTOR UPGRADE TASK - LeftAndMain::currentPage: use currentRecord() instead. */
        $admin->currentRecord();

        // 9. non-method replacement note (annotate only, NO auto-fix)
        /** @TODO SSU RECTOR UPGRADE TASK - ContentController::Menu: use getMenu() instead. You can continue to use $Menu in templates. */
        $controller->Menu();
        /** @TODO SSU RECTOR UPGRADE TASK - RemoteFileModalExtension::getRequest: use $this->getOwner()->getRequest() instead. */
        $ext->getRequest();

        // 2. unknown typed method call + fallback safe = true (should annotate & auto-rename)
        /** @TODO SSU RECTOR UPGRADE TASK - CMSMain::CanOrganiseSitetree: use canOrganiseTree instead. */
        $unknownObject->canOrganiseTree();

        // 3. unknown typed method call + fallback safe = false (should NOT annotate or rename)
        $unknownObject->currentPage();

        // 5. already annotated code (should rename if not already renamed, but not duplicate comment)
        /** @TODO SSU RECTOR UPGRADE TASK - LeftAndMain::currentPage: use currentRecord() instead. */
        $admin->currentRecord();

        // 6. class method with existing docblock (should append TODO & auto-rename)
        /**
         * Existing useful comment.
         * @TODO SSU RECTOR UPGRADE TASK - LeftAndMain::currentPage: use currentRecord() instead.
         */
        $admin->currentRecord();
    }
}