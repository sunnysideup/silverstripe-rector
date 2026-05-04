<?php

namespace SilverStripe\Forms {
    class FormField {
        /** @return static */
        public static function create() { return new static(); }
        public function displayIf($field) { return $this; }
        public function isEqualTo($val) { return $this; }
        public function isNotEqualTo($val) { return $this; }
        public function end() { return $this; }
    }
    class TextField extends FormField {}
    class DropdownField extends FormField {}
    class Wrapper extends FormField {}
    class FieldList {
        public function addFieldsToTab($tab, $fields) {}
        public function addFieldToTab($tab, $field) {}
        public function removeFieldsFromTab($tab, $fields) {}
        public function removeFieldFromTab($tab, $field) {}
    }
}

namespace App\Test {

    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\TextField;
    use SilverStripe\Forms\Wrapper;
    use SilverStripe\Forms\DropdownField;

    class SomeClass
    {
        public function getCMSFields()
        {
            $fields = new FieldList();

            // Test Case 1: Direct Object should be renamed
            $fields->addFieldsToTab('Root.Main', TextField::create('Name'));

            // Test Case 2: Object in Variable should be renamed via explicit type
            $singleField = new TextField('Title');
            $fields->addFieldsToTab('Root.Main', $singleField);

            // Test Case 3: Explicit Array should NOT be renamed (skipped)
            $fields->addFieldsToTab('Root.Main', [
                TextField::create('One'),
            ]);

            // Test Case 4: Array inside Variable should NOT be renamed
            $heroTabFields = [];
            $heroTabFields[] = Wrapper::create()->displayIf('HeroSize')->isNotEqualTo('Small')->end();
            if ($heroTabFields !== []) {
                $fields->addFieldsToTab('Root.Hero', $heroTabFields);
            }

            // Test Case 5: Direct String for Removal
            $fields->removeFieldsFromTab('Root.Main', 'Content');

            // Test Case 6: Direct Array for Removal (skipped)
            $fields->removeFieldsFromTab('Root.Main', ['Content', 'Sidebar']);
            
            // Test Case 7: Variable String for Removal
            $fieldToRemove = 'Header';
            $fields->removeFieldsFromTab('Root.Main', $fieldToRemove);
        }
    }
}
-----
<?php

namespace SilverStripe\Forms {
    class FormField {
        /** @return static */
        public static function create() { return new static(); }
        public function displayIf($field) { return $this; }
        public function isEqualTo($val) { return $this; }
        public function isNotEqualTo($val) { return $this; }
        public function end() { return $this; }
    }
    class TextField extends FormField {}
    class DropdownField extends FormField {}
    class Wrapper extends FormField {}
    class FieldList {
        public function addFieldsToTab($tab, $fields) {}
        public function addFieldToTab($tab, $field) {}
        public function removeFieldsFromTab($tab, $fields) {}
        public function removeFieldFromTab($tab, $field) {}
    }
}

namespace App\Test {

    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\TextField;
    use SilverStripe\Forms\Wrapper;
    use SilverStripe\Forms\DropdownField;

    class SomeClass
    {
        public function getCMSFields()
        {
            $fields = new FieldList();

            // Test Case 1: Direct Object should be renamed
            $fields->addFieldToTab('Root.Main', TextField::create('Name'));

            // Test Case 2: Object in Variable should be renamed via explicit type
            $singleField = new TextField('Title');
            $fields->addFieldToTab('Root.Main', $singleField);

            // Test Case 3: Explicit Array should NOT be renamed (skipped)
            $fields->addFieldsToTab('Root.Main', [
                TextField::create('One'),
            ]);

            // Test Case 4: Array inside Variable should NOT be renamed
            $heroTabFields = [];
            $heroTabFields[] = Wrapper::create()->displayIf('HeroSize')->isNotEqualTo('Small')->end();
            if ($heroTabFields !== []) {
                $fields->addFieldsToTab('Root.Hero', $heroTabFields);
            }

            // Test Case 5: Direct String for Removal
            $fields->removeFieldFromTab('Root.Main', 'Content');

            // Test Case 6: Direct Array for Removal (skipped)
            $fields->removeFieldsFromTab('Root.Main', ['Content', 'Sidebar']);
            
            // Test Case 7: Variable String for Removal
            $fieldToRemove = 'Header';
            $fields->removeFieldFromTab('Root.Main', $fieldToRemove);
        }
    }
}
