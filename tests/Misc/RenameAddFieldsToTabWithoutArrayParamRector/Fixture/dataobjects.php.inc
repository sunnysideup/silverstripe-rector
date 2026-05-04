<?php

namespace SilverStripe\Forms {
    class FormField {
        /** @return static */
        public static function create(...$args) { return new static(); }
    }
}
namespace SilverStripe\ORM {
    class DataObject {}
}

namespace App\Test {
    use SilverStripe\Forms\FormField;
    
    class SomeClass extends \SilverStripe\ORM\DataObject
    {
        public function getCMSFields() {
            $myfield = FormField::create();
            $fields->addFieldsToTab('Root.Main', $myfield);
        }
    }
}
-----
<?php

namespace SilverStripe\Forms {
    class FormField {
        /** @return static */
        public static function create(...$args) { return new static(); }
    }
}
namespace SilverStripe\ORM {
    class DataObject {}
}

namespace App\Test {
    use SilverStripe\Forms\FormField;
    
    class SomeClass extends \SilverStripe\ORM\DataObject
    {
        public function getCMSFields() {
            $myfield = FormField::create();
            $fields->addFieldToTab('Root.Main', $myfield);
        }
    }
}
