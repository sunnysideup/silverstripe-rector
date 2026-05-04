<?php

namespace SilverStripe\Forms {
    class FormField {
        /** @return static */
        public static function create(...$args) { return new static(); }
        public function setTitle($title) { return $this; }
        public function displayIf($field) { return $this; }
        public function isEqualTo($val) { return $this; }
        public function isNotEqualTo($val) { return $this; }
        public function end() { return $this; }
    }
    class TextField extends FormField {}
    class DropdownField extends FormField {}
    class HeaderField extends FormField {}
    class Wrapper extends FormField {}
    class FieldList {
        public function addFieldsToTab($tab, $fields) {}
        public function addFieldToTab($tab, $field) {}
        public function removeByName($name) {}
        public function insertAfter($name, $field) {}
    }
}

namespace App\Test {

    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\TextField;
    use SilverStripe\Forms\Wrapper;
    use SilverStripe\Forms\DropdownField;
    use SilverStripe\Forms\HeaderField;

    class SomeClass
    {
        public function getCMSFields()
        {
            $fields = new FieldList();

            // Test Case 1: Direct Object should be renamed
            $fields->addFieldsToTab('Root.Main', TextField::create('Name'));

            // Test Case 2: Object in Variable should be renamed
            $singleField = TextField::create('Title');
            $fields->addFieldsToTab('Root.Main', $singleField);

            // Test Case 3: Explicit Array should NOT be renamed (skipped)
            $fields->addFieldsToTab('Root.Main', [
                TextField::create('One'),
                TextField::create('Two'),
            ]);

            // Test Case 4: Array inside Variable should NOT be renamed
            $heroTabFields = [];
            $heroTabFields[] = Wrapper::create()->displayIf('HeroSize')->isNotEqualTo('Small')->end();
            if ($heroTabFields !== []) {
                $fields->addFieldsToTab('Root.Hero', $heroTabFields);
            }

            // Test Case 5: Nullsafe Object Method Call should be renamed
            $fields?->addFieldsToTab('Root.Nullsafe', TextField::create('Nullsafe'));

            // Test Case 6: Method call returning array should NOT be renamed
            $fields->addFieldsToTab('Root.MethodReturn', $this->getExtraFields());

            // Test Case 7: Method call returning single object should be renamed
            $fields->addFieldsToTab('Root.MethodReturnObj', $this->getSingleField());
        }

        // Test Case 8: Complex array iteration
        public function testComplexMethod()
        {
            $fields = new FieldList();
            $heroTabFields = [];

            if (true) {
                $heroSizeField = DropdownField::create('HeroSize');
                $heroTabFields[] = $heroSizeField;
            }

            $smallHeroFields = [];
            foreach (['HeroTitle' => 'Hero Title'] as $fieldName => $title) {
                $smallHeroFields[] = TextField::create($fieldName);
            }

            if ($smallHeroFields !== []) {
                $heroTabFields[] = Wrapper::create(...$smallHeroFields);
            }

            // THIS MUST REMAIN AS addFieldsToTab
            if ($heroTabFields !== []) {
                $fields->addFieldsToTab('Root.Hero', $heroTabFields);
            }

            return $fields;
        }

        private function getExtraFields(): array { return []; }
        private function getSingleField(): TextField { return TextField::create('Single'); }
    }
}
-----
<?php

namespace SilverStripe\Forms {
    class FormField {
        /** @return static */
        public static function create(...$args) { return new static(); }
        public function setTitle($title) { return $this; }
        public function displayIf($field) { return $this; }
        public function isEqualTo($val) { return $this; }
        public function isNotEqualTo($val) { return $this; }
        public function end() { return $this; }
    }
    class TextField extends FormField {}
    class DropdownField extends FormField {}
    class HeaderField extends FormField {}
    class Wrapper extends FormField {}
    class FieldList {
        public function addFieldsToTab($tab, $fields) {}
        public function addFieldToTab($tab, $field) {}
        public function removeByName($name) {}
        public function insertAfter($name, $field) {}
    }
}

namespace App\Test {

    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\TextField;
    use SilverStripe\Forms\Wrapper;
    use SilverStripe\Forms\DropdownField;
    use SilverStripe\Forms\HeaderField;

    class SomeClass
    {
        public function getCMSFields()
        {
            $fields = new FieldList();

            // Test Case 1: Direct Object should be renamed
            $fields->addFieldToTab('Root.Main', TextField::create('Name'));

            // Test Case 2: Object in Variable should be renamed
            $singleField = TextField::create('Title');
            $fields->addFieldToTab('Root.Main', $singleField);

            // Test Case 3: Explicit Array should NOT be renamed (skipped)
            $fields->addFieldsToTab('Root.Main', [
                TextField::create('One'),
                TextField::create('Two'),
            ]);

            // Test Case 4: Array inside Variable should NOT be renamed
            $heroTabFields = [];
            $heroTabFields[] = Wrapper::create()->displayIf('HeroSize')->isNotEqualTo('Small')->end();
            if ($heroTabFields !== []) {
                $fields->addFieldsToTab('Root.Hero', $heroTabFields);
            }

            // Test Case 5: Nullsafe Object Method Call should be renamed
            $fields?->addFieldToTab('Root.Nullsafe', TextField::create('Nullsafe'));

            // Test Case 6: Method call returning array should NOT be renamed
            $fields->addFieldsToTab('Root.MethodReturn', $this->getExtraFields());

            // Test Case 7: Method call returning single object should be renamed
            $fields->addFieldToTab('Root.MethodReturnObj', $this->getSingleField());
        }

        // Test Case 8: Complex array iteration
        public function testComplexMethod()
        {
            $fields = new FieldList();
            $heroTabFields = [];

            if (true) {
                $heroSizeField = DropdownField::create('HeroSize');
                $heroTabFields[] = $heroSizeField;
            }

            $smallHeroFields = [];
            foreach (['HeroTitle' => 'Hero Title'] as $fieldName => $title) {
                $smallHeroFields[] = TextField::create($fieldName);
            }

            if ($smallHeroFields !== []) {
                $heroTabFields[] = Wrapper::create(...$smallHeroFields);
            }

            // THIS MUST REMAIN AS addFieldsToTab
            if ($heroTabFields !== []) {
                $fields->addFieldsToTab('Root.Hero', $heroTabFields);
            }

            return $fields;
        }

        private function getExtraFields(): array { return []; }
        private function getSingleField(): TextField { return TextField::create('Single'); }
    }
}
