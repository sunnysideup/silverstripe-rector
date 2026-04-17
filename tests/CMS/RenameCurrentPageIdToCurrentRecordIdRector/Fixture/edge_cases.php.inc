<?php

namespace SilverStripe\CMS\Controllers {
    class ContentController {
        public function currentPageID() {
            return 1;
        }
    }
}

namespace App {
    use SilverStripe\CMS\Controllers\ContentController;

    class MyController extends ContentController {
        // Edge case 1: Already renamed - should skip
        public function currentRecordID() {
            return 1;
        }
    }

    class AnotherController extends ContentController {
        // Edge case 2: Method with different name - should skip
        public function getCurrentPage() {
            return 1;
        }
    }
}
-----
<?php

namespace SilverStripe\CMS\Controllers {
    class ContentController {
        public function currentPageID() {
            return 1;
        }
    }
}

namespace App {
    use SilverStripe\CMS\Controllers\ContentController;

    class MyController extends ContentController {
        // Edge case 1: Already renamed - should skip
        public function currentRecordID() {
            return 1;
        }
    }

    class AnotherController extends ContentController {
        // Edge case 2: Method with different name - should skip
        public function getCurrentPage() {
            return 1;
        }
    }
}