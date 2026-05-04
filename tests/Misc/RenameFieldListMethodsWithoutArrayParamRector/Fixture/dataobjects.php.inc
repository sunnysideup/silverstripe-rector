<?php

namespace App\Test {
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

namespace App\Test {
    class SomeClass extends \SilverStripe\ORM\DataObject
    {
        public function getCMSFields() {
            $myfield = FormField::create();
            $fields->addFieldToTab('Root.Main', $myfield);
        }
    }
}
