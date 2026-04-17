<?php

namespace SilverStripe\Core {
    class ClassInfo {
        public static function getValidSubClasses($class) { return []; }
    }
}

namespace SilverStripe\ORM {
    class DataObject {
        public static function singleton($class) { return new self(); }
        public function invokeWithExtensions($method, &$args) {}
    }
}

namespace SilverStripe\CMS\Model {
    class SiteTree {
        public static function page_type_classes() { return []; }
    }
}

namespace App {
    use SilverStripe\CMS\Model\SiteTree;

    class MyPage extends SiteTree
    {
        // Edge case 1: Static property assignment
        private static $pageTypes = [];

        // Edge case 2: Method chaining
        public function getTypes() {
            return SiteTree::page_type_classes();
        }

        // Edge case 3: Conditional usage
        public function hasTypes($flag) {
            if ($flag) {
                return SiteTree::page_type_classes();
            }
            return [];
        }

        // Edge case 4: Multiple class references
        public function checkAll() {
            $a = SiteTree::page_type_classes();
            $b = SiteTree::page_type_classes();
            return [$a, $b];
        }
    }
}
-----
<?php

namespace SilverStripe\Core {
    class ClassInfo {
        public static function getValidSubClasses($class) { return []; }
    }
}

namespace SilverStripe\ORM {
    class DataObject {
        public static function singleton($class) { return new self(); }
        public function invokeWithExtensions($method, &$args) {}
    }
}

namespace SilverStripe\CMS\Model {
    class SiteTree {
        public static function page_type_classes() { return []; }
    }
}

namespace App {
    use SilverStripe\CMS\Model\SiteTree;

    class MyPage extends SiteTree
    {
        // Edge case 1: Static property assignment - should still transform
        private static $pageTypes = [];

        // Edge case 2: Method chaining - should still transform
        public function getTypes() {
            $classes = \SilverStripe\Core\ClassInfo::getValidSubClasses(\SilverStripe\CMS\Model\SiteTree::class);
            \SilverStripe\ORM\DataObject::singleton(\SilverStripe\CMS\Model\SiteTree::class)->invokeWithExtensions('updateAllowedSubClasses', $classes);
            return $classes;
        }

        // Edge case 3: Conditional usage - should still transform
        public function hasTypes($flag) {
            if ($flag) {
                $classes = \SilverStripe\Core\ClassInfo::getValidSubClasses(\SilverStripe\CMS\Model\SiteTree::class);
                \SilverStripe\ORM\DataObject::singleton(\SilverStripe\CMS\Model\SiteTree::class)->invokeWithExtensions('updateAllowedSubClasses', $classes);
                return $classes;
            }
            return [];
        }

        // Edge case 4: Multiple class references - should transform all
        public function checkAll() {
            $classes = \SilverStripe\Core\ClassInfo::getValidSubClasses(\SilverStripe\CMS\Model\SiteTree::class);
            \SilverStripe\ORM\DataObject::singleton(\SilverStripe\CMS\Model\SiteTree::class)->invokeWithExtensions('updateAllowedSubClasses', $classes);
            $a = $classes;
            $classes = \SilverStripe\Core\ClassInfo::getValidSubClasses(\SilverStripe\CMS\Model\SiteTree::class);
            \SilverStripe\ORM\DataObject::singleton(\SilverStripe\CMS\Model\SiteTree::class)->invokeWithExtensions('updateAllowedSubClasses', $classes);
            $b = $classes;
            return [$a, $b];
        }
    }
}