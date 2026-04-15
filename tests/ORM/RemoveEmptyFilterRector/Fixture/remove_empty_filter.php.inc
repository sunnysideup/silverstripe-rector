<?php

namespace SilverStripe\ORM {
    class DataList {
        public function filter($args) { return $this; }
    }
}

namespace SilverStripe\CMS\Model {
    use SilverStripe\ORM\DataList;
    class SiteTree {
        /**
         * @return DataList
         */
        public static function get() { return new DataList(); }
    }
}

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

namespace SilverStripe\ORM {
    class DataList {
        public function filter($args) { return $this; }
    }
}

namespace SilverStripe\CMS\Model {
    use SilverStripe\ORM\DataList;
    class SiteTree {
        /**
         * @return DataList
         */
        public static function get() { return new DataList(); }
    }
}

namespace App {
    use SilverStripe\CMS\Model\SiteTree;

    class Test {
        public function run() {
            $list = SiteTree::get();
            $list2 = SiteTree::get()->filter('ID', 1);
        }
    }
}
