<?php

namespace App {
    use SilverStripe\CMS\Model\SiteTree;

    class Test {
        public function run() {
            $list = SiteTree::get()->filter('');
            $list2 = SiteTree::get()->filter("")->filter('ID', 1);
        }
    }
}
-----
<?php

namespace App {
    use SilverStripe\CMS\Model\SiteTree;

    class Test {
        public function run() {
            $list = SiteTree::get();
            $list2 = SiteTree::get()->filter('ID', 1);
        }
    }
}
