<?php

namespace App\Test {

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
        }

        // Test Case 6: Complex array iteration
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

            // THIS MUST REMAIN AS addFieldsToTab (Safe Fallback protects MixedType arrays)
            if ($heroTabFields !== []) {
                $fields->addFieldsToTab('Root.Hero', $heroTabFields);
            }

            return $fields;
        }
    }
}
-----
<?php

namespace App\Test {

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
        }

        // Test Case 6: Complex array iteration
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

            // THIS MUST REMAIN AS addFieldsToTab (Safe Fallback protects MixedType arrays)
            if ($heroTabFields !== []) {
                $fields->addFieldsToTab('Root.Hero', $heroTabFields);
            }

            return $fields;
        }
    }
}
