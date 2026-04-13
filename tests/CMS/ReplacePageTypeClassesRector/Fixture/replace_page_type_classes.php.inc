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
        protected function getClassList()
        {
            return SiteTree::page_type_classes();
        }

        public function checkInArray()
        {
            if (in_array('Page', SiteTree::page_type_classes())) {
                return true;
            }
            return false;
        }
        
        public function assignedValue()
        {
            $types = SiteTree::page_type_classes();
            return $types;
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
        protected function getClassList()
        {
            $classes = \SilverStripe\Core\ClassInfo::getValidSubClasses(\SilverStripe\CMS\Model\SiteTree::class);
            \SilverStripe\ORM\DataObject::singleton(\SilverStripe\CMS\Model\SiteTree::class)->invokeWithExtensions('updateAllowedSubClasses', $classes);
            return $classes;
        }

        public function checkInArray()
        {
            $classes = \SilverStripe\Core\ClassInfo::getValidSubClasses(\SilverStripe\CMS\Model\SiteTree::class);
            \SilverStripe\ORM\DataObject::singleton(\SilverStripe\CMS\Model\SiteTree::class)->invokeWithExtensions('updateAllowedSubClasses', $classes);
            if (in_array('Page', $classes)) {
                return true;
            }
            return false;
        }
        
        public function assignedValue()
        {
            $classes = \SilverStripe\Core\ClassInfo::getValidSubClasses(\SilverStripe\CMS\Model\SiteTree::class);
            \SilverStripe\ORM\DataObject::singleton(\SilverStripe\CMS\Model\SiteTree::class)->invokeWithExtensions('updateAllowedSubClasses', $classes);
            $types = $classes;
            return $types;
        }
    }
}
