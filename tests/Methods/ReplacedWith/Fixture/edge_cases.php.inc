<?php

namespace SilverStripe\UserForms {
    class UserForm {
        public function getCMSValidator() {}
    }
}

namespace SilverStripe\View {
    class SSViewer {
        public function fromString($str) {}
    }
}

namespace SilverStripe\CMS\Controllers {
    class CMSSiteTreeFilter {
        public function applyDefaultFilters() {}
    }
}

namespace App {
    use SilverStripe\UserForms\UserForm;
    use SilverStripe\View\SSViewer;
    use SilverStripe\CMS\Controllers\CMSSiteTreeFilter;

    class MyForm extends UserForm {
        // Test 1: Standard case - should auto-fix
        public function testStandard() {
            $result = $this->getCMSValidator();
        }

        // Test 2: Calling on variable with unknown type (fallback not allowed here)
        public function testUnknown($unknown) {
            $result = $unknown->getCMSValidator();
        }

        // Test 3: Class method override - should annotate and use replacement
        public function getCMSValidator() {
            return $this->getCMSCompositeValidator();
        }

        // Test 4: Variable with SSViewer type (fallback allowed)
        public function testViewer(SSViewer $viewer, $template) {
            $result = $viewer->fromString($template);
        }

        // Test 5: Variable with Filter type (should NOT auto-fix)
        public function testComplex(CMSSiteTreeFilter $filter) {
            $result = $filter->applyDefaultFilters();
        }
    }
}
-----
<?php

namespace SilverStripe\UserForms {
    class UserForm {
        // @TODO SSU RECTOR UPGRADE TASK - SilverStripe\UserForms\UserForm::getCMSValidator: replaced with getCMSCompositeValidator() FQCN: (SilverStripe\UserForms\UserForm)
        public function getCMSValidator() {}
    }
}

namespace SilverStripe\View {
    class SSViewer {
        // @TODO SSU RECTOR UPGRADE TASK - SilverStripe\View\SSViewer::fromString: replaced with SSTemplateEngine::renderString() FQCN: (SilverStripe\View\SSViewer)
        public function fromString($str) {}
    }
}

namespace SilverStripe\CMS\Controllers {
    class CMSSiteTreeFilter {
        // @TODO SSU RECTOR UPGRADE TASK - SilverStripe\CMS\Controllers\CMSSiteTreeFilter::applyDefaultFilters: replaced with a SearchContext subclass FQCN: (SilverStripe\CMS\Controllers\CMSSiteTreeFilter)
        public function applyDefaultFilters() {}
    }
}

namespace App {
    use SilverStripe\UserForms\UserForm;
    use SilverStripe\View\SSViewer;
    use SilverStripe\CMS\Controllers\CMSSiteTreeFilter;

    class MyForm extends UserForm {
        // Test 1: Standard case - should auto-fix
        public function testStandard() {
            // @TODO SSU RECTOR UPGRADE TASK - SilverStripe\UserForms\UserForm::getCMSValidator: replaced with getCMSCompositeValidator() FQCN: (SilverStripe\UserForms\UserForm)
            $result = $this->getCMSCompositeValidator();
        }

        // Test 2: Calling on variable with unknown type (fallback not allowed here)
        public function testUnknown($unknown) {
            // @TODO SSU RECTOR UPGRADE TASK - SilverStripe\UserForms\UserForm::getCMSValidator: replaced with getCMSCompositeValidator() FQCN: (SilverStripe\UserForms\UserForm)
            $result = $unknown->getCMSValidator();
        }

        // Test 3: Class method override - should annotate and use replacement
        // @TODO SSU RECTOR UPGRADE TASK - SilverStripe\UserForms\UserForm::getCMSValidator: replaced with getCMSCompositeValidator() FQCN: (SilverStripe\UserForms\UserForm)
        public function getCMSValidator() {
            return $this->getCMSCompositeValidator();
        }

        // Test 4: Variable with SSViewer type (fallback allowed)
        public function testViewer(SSViewer $viewer, $template) {
            // @TODO SSU RECTOR UPGRADE TASK - SilverStripe\View\SSViewer::fromString: replaced with SSTemplateEngine::renderString() FQCN: (SilverStripe\View\SSViewer)
            $result = $viewer->renderString($template);
        }

        // Test 5: Variable with Filter type (should NOT auto-fix)
        public function testComplex(CMSSiteTreeFilter $filter) {
            // @TODO SSU RECTOR UPGRADE TASK - SilverStripe\CMS\Controllers\CMSSiteTreeFilter::applyDefaultFilters: replaced with a SearchContext subclass FQCN: (SilverStripe\CMS\Controllers\CMSSiteTreeFilter)
            $result = $filter->applyDefaultFilters();
        }
    }
}