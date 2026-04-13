<?php

namespace SilverStripe\CMS\Controllers {
    class CMSPageEditController {
        public function currentPageID() {
            return 1;
        }
        public function currentRecordID() {
            return 1;
        }
    }
}

namespace App {
    use SilverStripe\CMS\Controllers\CMSPageEditController;

    class MyCustomEditController extends CMSPageEditController
    {
        public function doSomething()
        {
            $id = $this->currentPageID();
            return $id;
        }
        
        public function actOnAnotherInstance(CMSPageEditController $controller)
        {
            return $controller->currentPageID();
        }
    }
}
-----
<?php

namespace SilverStripe\CMS\Controllers {
    class CMSPageEditController {
        public function currentPageID() {
            return 1;
        }
        public function currentRecordID() {
            return 1;
        }
    }
}

namespace App {
    use SilverStripe\CMS\Controllers\CMSPageEditController;

    class MyCustomEditController extends CMSPageEditController
    {
        public function doSomething()
        {
            $id = $this->currentRecordID();
            return $id;
        }
        
        public function actOnAnotherInstance(CMSPageEditController $controller)
        {
            return $controller->currentRecordID();
        }
    }
}
