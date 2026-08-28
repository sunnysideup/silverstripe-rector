<?php

namespace App {
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\FormField;

    class SomeClass extends \SilverStripe\ORM\DataObject
    {
        public function getCMSFields() {
            $fields = FieldList::create();
            
            $myfield = FormField::create('Title');
            $heroTabFields = [FormField::create('Image')];

            // Case 8: Variable with "field" matches heuristic -> Renames
            $fields->addFieldsToTab('Root.Main', $myfield);
            
            // Case 9: Variable with "fields" matches heuristic -> Ignores
            $fields->addFieldsToTab('Root.Main', $heroTabFields);
        }
    }
}
-----
<?php

namespace App {
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\FormField;

    class SomeClass extends \SilverStripe\ORM\DataObject
    {
        public function getCMSFields() {
            $fields = FieldList::create();
            
            $myfield = FormField::create('Title');
            $heroTabFields = [FormField::create('Image')];

            // Case 8: Variable with "field" matches heuristic -> Renames
            $fields->addFieldToTab('Root.Main', $myfield);
            
            // Case 9: Variable with "fields" matches heuristic -> Ignores
            $fields->addFieldsToTab('Root.Main', $heroTabFields);
        }
    }
}
