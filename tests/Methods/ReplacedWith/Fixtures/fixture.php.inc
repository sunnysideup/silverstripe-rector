<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\ReplacedWith\Fixtures;

use SilverStripe\UserForms\UserForm;
use SilverStripe\View\SSViewer;
use SilverStripe\CMS\Controllers\CMSSiteTreeFilter;

class UserForm {
    public function getCMSValidator() {}
}

class SSViewer {
    public function fromString() {}
}

class CMSSiteTreeFilter {
    public function applyDefaultFilters() {}
}

class CustomForm extends UserForm {
    // 4 & 8. overriding class method in subclass (should annotate & auto-rename safely)
    public function getCMSValidator() {}
}

class Demo {
    public function execute(UserForm $form, SSViewer $viewer, CMSSiteTreeFilter $filter, $unknownObject) {
        
        // 1 & 7. known typed method call (should annotate & auto-rename safely)
        $form->getCMSValidator();

        // 9. non-method replacement note (annotate only, NO auto-fix)
        $viewer->fromString();
        $filter->applyDefaultFilters();

        // 2. unknown typed method call + fallback safe = true (should annotate only, due to complex note)
        $unknownObject->fromString();

        // 3. unknown typed method call + fallback safe = false (should NOT annotate or rename)
        $unknownObject->getCMSValidator();

        // 5. already annotated code (should rename if not already renamed, but not duplicate comment)
        /** @TODO SSU RECTOR UPGRADE TASK - SilverStripe\UserForms\UserForm::getCMSValidator: replaced with getCMSCompositeValidator() */
        $form->getCMSValidator();

        // 6. class method with existing docblock (should append TODO & auto-rename)
        /**
         * Existing useful comment.
         */
        $form->getCMSValidator();
    }
}
-----
<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\ReplacedWith\Fixtures;

use SilverStripe\UserForms\UserForm;
use SilverStripe\View\SSViewer;
use SilverStripe\CMS\Controllers\CMSSiteTreeFilter;

class UserForm {
    public function getCMSValidator() {}
}

class SSViewer {
    public function fromString() {}
}

class CMSSiteTreeFilter {
    public function applyDefaultFilters() {}
}

class CustomForm extends UserForm {
    // 4 & 8. overriding class method in subclass (should annotate & auto-rename safely)
    /** @TODO SSU RECTOR UPGRADE TASK - SilverStripe\UserForms\UserForm::getCMSValidator: replaced with getCMSCompositeValidator() */
    public function getCMSCompositeValidator() {}
}

class Demo {
    public function execute(UserForm $form, SSViewer $viewer, CMSSiteTreeFilter $filter, $unknownObject) {
        
        // 1 & 7. known typed method call (should annotate & auto-rename safely)
        /** @TODO SSU RECTOR UPGRADE TASK - SilverStripe\UserForms\UserForm::getCMSValidator: replaced with getCMSCompositeValidator() */
        $form->getCMSCompositeValidator();

        // 9. non-method replacement note (annotate only, NO auto-fix)
        /** @TODO SSU RECTOR UPGRADE TASK - SilverStripe\View\SSViewer::fromString: replaced with SSTemplateEngine::renderString() */
        $viewer->fromString();
        /** @TODO SSU RECTOR UPGRADE TASK - SilverStripe\CMS\Controllers\CMSSiteTreeFilter::applyDefaultFilters: replaced with a SearchContext subclass */
        $filter->applyDefaultFilters();

        // 2. unknown typed method call + fallback safe = true (should annotate only, due to complex note)
        /** @TODO SSU RECTOR UPGRADE TASK - SilverStripe\View\SSViewer::fromString: replaced with SSTemplateEngine::renderString() */
        $unknownObject->fromString();

        // 3. unknown typed method call + fallback safe = false (should NOT annotate or rename)
        $unknownObject->getCMSValidator();

        // 5. already annotated code (should rename if not already renamed, but not duplicate comment)
        /** @TODO SSU RECTOR UPGRADE TASK - SilverStripe\UserForms\UserForm::getCMSValidator: replaced with getCMSCompositeValidator() */
        $form->getCMSCompositeValidator();

        // 6. class method with existing docblock (should append TODO & auto-rename)
        /**
         * Existing useful comment.
         * @TODO SSU RECTOR UPGRADE TASK - SilverStripe\UserForms\UserForm::getCMSValidator: replaced with getCMSCompositeValidator()
         */
        $form->getCMSCompositeValidator();
    }
}