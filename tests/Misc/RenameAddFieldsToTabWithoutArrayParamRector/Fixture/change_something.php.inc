<?php

namespace App\Test {

    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\TextField;
    use SilverStripe\Forms\Wrapper;

    class SomeClass
    {
        /** @var array */
        private $fieldArray = [];

        /** @var TextField */
        private $singleFieldProp;

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

            // Test Case 4: Array inside Variable should NOT be renamed (skipped - fixes bug)
            $heroTabFields = [];
            $heroTabFields[] = Wrapper::create()->displayIf('HeroSize')->isNotEqualTo('Small')->end();
            if ($heroTabFields !== []) {
                $fields->addFieldsToTab('Root.Hero', $heroTabFields);
            }

            // Test Case 5: Nullsafe Object Method Call should be renamed
            $fields?->addFieldsToTab('Root.Nullsafe', TextField::create('Nullsafe'));

            // Test Case 6: Method call returning array should NOT be renamed (skipped)
            $fields->addFieldsToTab('Root.MethodReturn', $this->getExtraFields());

            // Test Case 7: Method call returning single object should be renamed
            $fields->addFieldsToTab('Root.MethodReturnObj', $this->getSingleField());

            // Test Case 8: Property typed as array should NOT be renamed
            $fields->addFieldsToTab('Root.PropArray', $this->fieldArray);

            // Test Case 9: Property typed as object should be renamed
            $fields->addFieldsToTab('Root.PropObj', $this->singleFieldProp);

            // Test Case 10: Array functions like array_merge should NOT be renamed
            $fields->addFieldsToTab('Root.ArrayMerge', array_merge([TextField::create('A')], [TextField::create('B')]));

            // Test Case 11: Instantiating with 'new' keyword directly should be renamed
            $fields->addFieldsToTab('Root.NewKeyword', new TextField('NewKeyword'));

            // Test Case 12: Inline docblock defining array should NOT be renamed
            /** @var array $docblockArray */
            $docblockArray = $this->getMixedData();
            $fields->addFieldsToTab('Root.Docblock', $docblockArray);

            // Test Case 13: Inline docblock defining single object should be renamed
            /** @var TextField $docblockObj */
            $docblockObj = $this->getMixedData();
            $fields->addFieldsToTab('Root.DocblockObj', $docblockObj);
        }

        /**
         * @return array
         */
        private function getExtraFields(): array
        {
            return [];
        }

        private function getSingleField(): TextField
        {
            return TextField::create('Single');
        }

        private function getMixedData()
        {
            return null;
        }
    }
}
-----
<?php

namespace App\Test {

    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\TextField;
    use SilverStripe\Forms\Wrapper;

    class SomeClass
    {
        /** @var array */
        private $fieldArray = [];

        /** @var TextField */
        private $singleFieldProp;

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

            // Test Case 4: Array inside Variable should NOT be renamed (skipped - fixes bug)
            $heroTabFields = [];
            $heroTabFields[] = Wrapper::create()->displayIf('HeroSize')->isNotEqualTo('Small')->end();
            if ($heroTabFields !== []) {
                $fields->addFieldsToTab('Root.Hero', $heroTabFields);
            }

            // Test Case 5: Nullsafe Object Method Call should be renamed
            $fields?->addFieldToTab('Root.Nullsafe', TextField::create('Nullsafe'));

            // Test Case 6: Method call returning array should NOT be renamed (skipped)
            $fields->addFieldsToTab('Root.MethodReturn', $this->getExtraFields());

            // Test Case 7: Method call returning single object should be renamed
            $fields->addFieldToTab('Root.MethodReturnObj', $this->getSingleField());

            // Test Case 8: Property typed as array should NOT be renamed
            $fields->addFieldsToTab('Root.PropArray', $this->fieldArray);

            // Test Case 9: Property typed as object should be renamed
            $fields->addFieldToTab('Root.PropObj', $this->singleFieldProp);

            // Test Case 10: Array functions like array_merge should NOT be renamed
            $fields->addFieldsToTab('Root.ArrayMerge', array_merge([TextField::create('A')], [TextField::create('B')]));

            // Test Case 11: Instantiating with 'new' keyword directly should be renamed
            $fields->addFieldToTab('Root.NewKeyword', new TextField('NewKeyword'));

            // Test Case 12: Inline docblock defining array should NOT be renamed
            /** @var array $docblockArray */
            $docblockArray = $this->getMixedData();
            $fields->addFieldsToTab('Root.Docblock', $docblockArray);

            // Test Case 13: Inline docblock defining single object should be renamed
            /** @var TextField $docblockObj */
            $docblockObj = $this->getMixedData();
            $fields->addFieldToTab('Root.DocblockObj', $docblockObj);
        }

        /**
         * @return array
         */
        private function getExtraFields(): array
        {
            return [];
        }

        private function getSingleField(): TextField
        {
            return TextField::create('Single');
        }

        private function getMixedData()
        {
            return null;
        }
    }
}
