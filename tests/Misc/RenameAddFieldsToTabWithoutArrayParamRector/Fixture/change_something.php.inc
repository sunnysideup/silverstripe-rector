<?php

namespace App\Test {

    class SomeClass
    {
        public function getCMSFields()
        {
            $fields = new FieldList();

            // Test Case 1: Direct Object should be renamed
            $fields->addFieldsToTab('Root.Main', TextField::create('Name'));

            // Test Case 2: Object in Variable should be renamed (matches heuristic because singular 'Field')
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

            // Test Case 6: Method call returning array should NOT be renamed (matches heuristic plural 'Fields')
            $fields->addFieldsToTab('Root.MethodReturn', $this->getExtraFields());

            // Test Case 7: Method call returning single object should be renamed (matches heuristic singular)
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

            // THIS MUST REMAIN AS addFieldsToTab (matches heuristic 'Fields')
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

namespace App\Test {

    class SomeClass
    {
        public function getCMSFields()
        {
            $fields = new FieldList();

            // Test Case 1: Direct Object should be renamed
            $fields->addFieldToTab('Root.Main', TextField::create('Name'));

            // Test Case 2: Object in Variable should be renamed (matches heuristic because singular 'Field')
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

            // Test Case 6: Method call returning array should NOT be renamed (matches heuristic plural 'Fields')
            $fields->addFieldsToTab('Root.MethodReturn', $this->getExtraFields());

            // Test Case 7: Method call returning single object should be renamed (matches heuristic singular)
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

            // THIS MUST REMAIN AS addFieldsToTab (matches heuristic 'Fields')
            if ($heroTabFields !== []) {
                $fields->addFieldsToTab('Root.Hero', $heroTabFields);
            }

            return $fields;
        }

        private function getExtraFields(): array { return []; }
        private function getSingleField(): TextField { return TextField::create('Single'); }
    }
}
