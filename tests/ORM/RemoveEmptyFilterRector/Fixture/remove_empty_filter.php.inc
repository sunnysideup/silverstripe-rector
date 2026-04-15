<?php

namespace App {
    use SilverStripe\CMS\Model\SiteTree;

    class Test {
        public function run() {
            // Case 1: Direct chain
            $list = SiteTree::get()->filter('');
            
            // Case 2: Double quotes
            $list2 = SiteTree::get()->filter("");
            
            // Case 3: Preserving other filters in chain
            $list3 = SiteTree::get()->filter('')->filter('ID', 1);
        }
    }
}
-----
<?php

namespace App {
    use SilverStripe\CMS\Model\SiteTree;

    class Test {
        public function run() {
            // Case 1: Direct chain
            $list = SiteTree::get();
            
            // Case 2: Double quotes
            $list2 = SiteTree::get();
            
            // Case 3: Preserving other filters in chain
            $list3 = SiteTree::get()->filter('ID', 1);
        }
    }
}
