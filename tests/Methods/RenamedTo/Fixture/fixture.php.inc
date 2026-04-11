<?php

namespace TractorCow\Fluent\Extension {
    class FluentSiteTreeExtension
    {
        public function MetaTags($params) {}
    }
}

namespace SilverStripe\MFA\Extension {
    class RequirementsExtension
    {
        public function init() {}
    }
}

namespace SilverStripe\ORM {
    class CMSPreviewable
    {
        public function CMSEditLink() {}
    }
}

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\RenamedTo\Fixture {

    use SilverStripe\MFA\Extension\RequirementsExtension;
    use SilverStripe\ORM\CMSPreviewable;
    use TractorCow\Fluent\Extension\FluentSiteTreeExtension;

    class CustomExtension extends RequirementsExtension
    {
        // 4 & 8. overriding class method in subclass (should annotate & auto-rename safely)
        public function init() {}
    }

    class Demo
    {
        public function execute(FluentSiteTreeExtension $extension, CMSPreviewable $preview, $unknownObject)
        {
            // 1 & 7. known typed method call (should annotate & auto-rename safely)
            $extension->MetaTags('params');

            // 2. unknown typed method call + fallback safe = true (should annotate & auto-rename)
            $unknownObject->init();

            // 3. unknown typed method call + fallback safe = false (should NOT annotate or rename)
            $unknownObject->MetaTags('params');

            // 5. already annotated code (should rename if not already renamed, but not duplicate comment)
            // @TODO SSU RECTOR UPGRADE TASK - FluentSiteTreeExtension::MetaTags: renamed to updateMetaTags() FQCN: (TractorCow\Fluent\Extension\FluentSiteTreeExtension) 
            $extension->MetaTags('params');

            // 6. class method with existing docblock (should append TODO & auto-rename)
            /**
             * Existing Comments
             */
            $extension->MetaTags('params');

            // 9. ambiguous note format (should annotate only, NO rename)
            $preview->CMSEditLink();
        }
    }
}
-----
<?php

namespace TractorCow\Fluent\Extension {
    class FluentSiteTreeExtension
    {
        // @TODO SSU RECTOR UPGRADE TASK - FluentSiteTreeExtension::MetaTags: renamed to updateMetaTags() FQCN: (TractorCow\Fluent\Extension\FluentSiteTreeExtension) 
        public function updateMetaTags($params) {}
    }
}

namespace SilverStripe\MFA\Extension {
    class RequirementsExtension
    {
        // @TODO SSU RECTOR UPGRADE TASK - RequirementsExtension::init: renamed to onInit() FQCN: (SilverStripe\MFA\Extension\RequirementsExtension) 
        public function onInit() {}
    }
}

namespace SilverStripe\ORM {
    class CMSPreviewable
    {
        // @TODO SSU RECTOR UPGRADE TASK - CMSPreviewable::CMSEditLink: changed to something entirely different FQCN: (SilverStripe\ORM\CMSPreviewable) 
        public function CMSEditLink() {}
    }
}

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\RenamedTo\Fixture {

    use SilverStripe\MFA\Extension\RequirementsExtension;
    use SilverStripe\ORM\CMSPreviewable;
    use TractorCow\Fluent\Extension\FluentSiteTreeExtension;

    class CustomExtension extends RequirementsExtension
    {
        // 4 & 8. overriding class method in subclass (should annotate & auto-rename safely)
        // @TODO SSU RECTOR UPGRADE TASK - RequirementsExtension::init: renamed to onInit() FQCN: (SilverStripe\MFA\Extension\RequirementsExtension) 
        public function onInit() {}
    }

    class Demo
    {
        public function execute(FluentSiteTreeExtension $extension, CMSPreviewable $preview, $unknownObject)
        {
            // 1 & 7. known typed method call (should annotate & auto-rename safely)
            // @TODO SSU RECTOR UPGRADE TASK - FluentSiteTreeExtension::MetaTags: renamed to updateMetaTags() FQCN: (TractorCow\Fluent\Extension\FluentSiteTreeExtension) 
            $extension->updateMetaTags('params');

            // 2. unknown typed method call + fallback safe = true (should annotate & auto-rename)
            // @TODO SSU RECTOR UPGRADE TASK - RequirementsExtension::init: renamed to onInit() FQCN: (SilverStripe\MFA\Extension\RequirementsExtension) 
            $unknownObject->onInit();

            // 3. unknown typed method call + fallback safe = false (should NOT annotate or rename)
            $unknownObject->MetaTags('params');

            // 5. already annotated code (should rename if not already renamed, but not duplicate comment)
            // @TODO SSU RECTOR UPGRADE TASK - FluentSiteTreeExtension::MetaTags: renamed to updateMetaTags() FQCN: (TractorCow\Fluent\Extension\FluentSiteTreeExtension) 
            $extension->updateMetaTags('params');

            // 6. class method with existing docblock (should append TODO & auto-rename)
            /**
             * Existing Comments
             */
            // @TODO SSU RECTOR UPGRADE TASK - FluentSiteTreeExtension::MetaTags: renamed to updateMetaTags() FQCN: (TractorCow\Fluent\Extension\FluentSiteTreeExtension)
            $extension->updateMetaTags('params');

            // 9. ambiguous note format (should annotate only, NO rename)
            // @TODO SSU RECTOR UPGRADE TASK - CMSPreviewable::CMSEditLink: changed to something entirely different FQCN: (SilverStripe\ORM\CMSPreviewable) 
            $preview->CMSEditLink();
        }
    }
}