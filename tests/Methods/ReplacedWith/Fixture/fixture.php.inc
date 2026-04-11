<?php

namespace SilverStripe\UserForms {
    class UserForm {
        public function getCMSValidator() {}
    }
}

namespace SilverStripe\View {
    class SSViewer {
        public function fromString() {}
    }
}

namespace SilverStripe\CMS\Controllers {
    class CMSSiteTreeFilter {
        public function applyDefaultFilters() {}
    }
}

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\ReplacedWith\Fixture {

    use SilverStripe\UserForms\UserForm;
    use SilverStripe\View\SSViewer;
    use SilverStripe\CMS\Controllers\CMSSiteTreeFilter;

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
            // @TODO SSU RECTOR UPGRADE TASK - UserForm::getCMSValidator: replaced with getCMSCompositeValidator() FQCN: (SilverStripe\UserForms\UserForm)            $form->getCMSValidator();

            // 6. class method with existing docblock (should append TODO & auto-rename)
            /**
             * Existing Comments
             */
            $form->getCMSValidator();
        }
    }
}
-----
<?php

namespace SilverStripe\UserForms {
    class UserForm {
        // @TODO SSU RECTOR UPGRADE TASK - UserForm::getCMSValidator: replaced with getCMSCompositeValidator() FQCN: (SilverStripe\UserForms\UserForm)        public function getCMSCompositeValidator() {}
    }
}

namespace SilverStripe\View {
    class SSViewer {
        // @TODO SSU RECTOR UPGRADE TASK - SSViewer::fromString: replaced with SSTemplateEngine::renderString() FQCN: (SilverStripe\View\SSViewer)        public function fromString() {}
    }
}

namespace SilverStripe\CMS\Controllers {
    class CMSSiteTreeFilter {
        // @TODO SSU RECTOR UPGRADE TASK - CMSSiteTreeFilter::applyDefaultFilters: replaced with a SearchContext subclass FQCN: (SilverStripe\CMS\Controllers\CMSSiteTreeFilter)        public function applyDefaultFilters() {}
    }
}

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\ReplacedWith\Fixture {

    use SilverStripe\UserForms\UserForm;
    use SilverStripe\View\SSViewer;
    use SilverStripe\CMS\Controllers\CMSSiteTreeFilter;

    class CustomForm extends UserForm {
        // 4 & 8. overriding class method in subclass (should annotate & auto-rename safely)
        // @TODO SSU RECTOR UPGRADE TASK - UserForm::getCMSValidator: replaced with getCMSCompositeValidator() FQCN: (SilverStripe\UserForms\UserForm)        
        public function getCMSCompositeValidator() {}
    }

    class Demo {
        public function execute(UserForm $form, SSViewer $viewer, CMSSiteTreeFilter $filter, $unknownObject) {
            
            // 1 & 7. known typed method call (should annotate & auto-rename safely)
            // @TODO SSU RECTOR UPGRADE TASK - UserForm::getCMSValidator: replaced with getCMSCompositeValidator() FQCN: (SilverStripe\UserForms\UserForm)            
            $form->getCMSCompositeValidator();

            // 9. non-method replacement note (annotate only, NO auto-fix)
            // @TODO SSU RECTOR UPGRADE TASK - SSViewer::fromString: replaced with SSTemplateEngine::renderString() FQCN: (SilverStripe\View\SSViewer)            
            $viewer->fromString();
            // @TODO SSU RECTOR UPGRADE TASK - CMSSiteTreeFilter::applyDefaultFilters: replaced with a SearchContext subclass FQCN: (SilverStripe\CMS\Controllers\CMSSiteTreeFilter)            
            $filter->applyDefaultFilters();

            // 2. unknown typed method call + fallback safe = true (should annotate only, due to complex note)
            // @TODO SSU RECTOR UPGRADE TASK - SSViewer::fromString: replaced with SSTemplateEngine::renderString() FQCN: (SilverStripe\View\SSViewer)            
            $unknownObject->fromString();

            // 3. unknown typed method call + fallback safe = false (should NOT annotate or rename)
            $unknownObject->getCMSValidator();

            // 5. already annotated code (should rename if not already renamed, but not duplicate comment)
            // @TODO SSU RECTOR UPGRADE TASK - UserForm::getCMSValidator: replaced with getCMSCompositeValidator() FQCN: (SilverStripe\UserForms\UserForm)            
            $form->getCMSCompositeValidator();

            // 6. class method with existing docblock (should append TODO & auto-rename)
            /**
             * Existing Comments
             */
            // @TODO SSU RECTOR UPGRADE TASK - UserForm::getCMSValidator: replaced with getCMSCompositeValidator() FQCN: (SilverStripe\UserForms\UserForm)
            $form->getCMSCompositeValidator();
        }
    }
}