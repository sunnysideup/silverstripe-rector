<?php

namespace App {
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\TextField;

    class SomeController 
    {
        public function getFields($unknownVariable)
        {
            $fields = FieldList::create();
            
            // Case 1: Explicit Static Create (Renames)
            $fields->addFieldsToTab('Root.Main', TextField::create('Name'));
            
            // Case 2: Explicit New (Renames)
            $fields->addFieldsToTab('Root.Main', new TextField('Email'));
            
            // Case 3: Explicit Array (Ignores)
            $fields->addFieldsToTab('Root.Main', [TextField::create('Age')]);
            
            // Case 4: Explicit String for Removal (Renames)
            $fields->removeFieldsFromTab('Root.Main', 'Content');
            
            // Case 5: FieldList Object (Ignores)
            $fields->addFieldsToTab('Root.Main', FieldList::create());
            
            // Case 6: Unknown Variable to Add (Adds TODO)
            $fields->addFieldsToTab('Root.Main', $unknownVariable);
            
            // Case 7: Unknown Method Return to Remove (Adds TODO)
            $fields->removeFieldsFromTab('Root.Main', $this->getDynamicFields());
        }
        
        public function getDynamicFields()
        {
            return $_GET['fields'] ?? null;
        }
    }
}
-----
<?php

namespace App {
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\TextField;

    class SomeController 
    {
        public function getFields($unknownVariable)
        {
            $fields = FieldList::create();
            
            // Case 1: Explicit Static Create (Renames)
            $fields->addFieldToTab('Root.Main', TextField::create('Name'));
            
            // Case 2: Explicit New (Renames)
            $fields->addFieldToTab('Root.Main', new TextField('Email'));
            
            // Case 3: Explicit Array (Ignores)
            $fields->addFieldsToTab('Root.Main', [TextField::create('Age')]);
            
            // Case 4: Explicit String for Removal (Renames)
            $fields->removeFieldFromTab('Root.Main', 'Content');
            
            // Case 5: FieldList Object (Ignores)
            $fields->addFieldsToTab('Root.Main', FieldList::create());
            
            // Case 6: Unknown Variable to Add (Adds TODO)
            // @TODO SSU RECTOR UPGRADE TASK: FieldList::addFieldsToTab() - Mixed type detected. If this is a single field/string, change to addFieldToTab() manually.
            $fields->addFieldsToTab('Root.Main', $unknownVariable);
            
            // Case 7: Unknown Method Return to Remove (Adds TODO)
            // @TODO SSU RECTOR UPGRADE TASK: FieldList::removeFieldsFromTab() - Mixed type detected. If this is a single field/string, change to removeFieldFromTab() manually.
            $fields->removeFieldsFromTab('Root.Main', $this->getDynamicFields());
        }
        
        public function getDynamicFields()
        {
            return $_GET['fields'] ?? null;
        }
    }
}
