<?php

namespace App\Test {

    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\TextField;
    use SilverStripe\Forms\Wrapper;
    use SilverStripe\Forms\DropdownField;
    use SilverStripe\Forms\HeaderField;
    use SilverStripe\Forms\GridField\GridField;

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

            // Test Case 4: Simple Array inside Variable should NOT be renamed
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

        // Test Case 14: Complex Conditional Arrays (from user issue)
        public function testComplexMethod()
        {
            $fields = $this->getFieldsSomehow();

            $fetchField = static function (string $name) use ($fields) {
                if (! ($field = $fields->dataFieldByName($name))) {
                    return null;
                }
                $fields->removeByName($name, true);
                return $field;
            };

            if ($teReoField = $fetchField('TeReoTitle')) {
                $teReoField->setTitle('Te Reo Title');
                $fields->insertAfter('MenuTitle', $teReoField);
            }

            if ($searchField = $fetchField('SearchDesc')) {
                $searchField->setTitle('Search Description');
                $fields->addFieldToTab('Root.Search', $searchField);
            }

            $heroFieldNames = ['HeroSize', 'HeroTitle'];

            if (! $this->canHaveAHero()) {
                $fields->removeByName($heroFieldNames, true);
                return $fields;
            }

            // High complexity array iteration
            $heroTabFields = [];

            if ($heroSizeField = $fetchField('HeroSize')) {
                $heroSizeOptions = ['Large' => 'Large', 'Medium' => 'Medium'];
                if ($heroSizeField instanceof DropdownField) {
                    $heroSizeField->setSource($heroSizeOptions);
                } else {
                    $heroSizeField = DropdownField::create('HeroSize', 'Hero Size', $heroSizeOptions, $this->HeroSize);
                }
                $heroSizeField->setTitle('Hero Size');
                $heroTabFields[] = $heroSizeField;
            }

            $smallHeroFields = [];
            foreach (['HeroTitle' => 'Hero Title'] as $fieldName => $title) {
                if ($field = $fetchField($fieldName)) {
                    $field->setTitle($title);
                    $smallHeroFields[] = $field;
                }
            }

            $smallHeroFields[] = HeaderField::create('Overlay', 'Overlay settings');

            if ($smallHeroFields !== []) {
                $heroTabFields[] = Wrapper::create(...$smallHeroFields)
                    ->displayIf('HeroSize')->isEqualTo('Small')->end();
            }

            $nonSmallFields = [];

            if ($displayArrowField = $fetchField('HeroDisplayArrow')) {
                $displayArrowField->setTitle('Display scroll arrow?');
                $nonSmallFields[] = $displayArrowField;
            }

            if ($nonSmallFields !== []) {
                $heroTabFields[] = Wrapper::create(...$nonSmallFields)
                    ->displayIf('HeroSize')->isNotEqualTo('Small')->end();
            }

            // THIS MUST REMAIN AS addFieldsToTab
            if ($heroTabFields !== []) {
                $fields->addFieldsToTab('Root.Hero', $heroTabFields);
            }

            return $fields;
        }

        private function getExtraFields(): array { return []; }
        private function getSingleField(): TextField { return TextField::create('Single'); }
        private function getMixedData() { return null; }
        private function getFieldsSomehow() { return new FieldList(); }
        private function canHaveAHero() { return true; }
    }
}
-----
<?php

namespace App\Test {

    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\TextField;
    use SilverStripe\Forms\Wrapper;
    use SilverStripe\Forms\DropdownField;
    use SilverStripe\Forms\HeaderField;
    use SilverStripe\Forms\GridField\GridField;

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

            // Test Case 4: Simple Array inside Variable should NOT be renamed
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

        // Test Case 14: Complex Conditional Arrays (from user issue)
        public function testComplexMethod()
        {
            $fields = $this->getFieldsSomehow();

            $fetchField = static function (string $name) use ($fields) {
                if (! ($field = $fields->dataFieldByName($name))) {
                    return null;
                }
                $fields->removeByName($name, true);
                return $field;
            };

            if ($teReoField = $fetchField('TeReoTitle')) {
                $teReoField->setTitle('Te Reo Title');
                $fields->insertAfter('MenuTitle', $teReoField);
            }

            if ($searchField = $fetchField('SearchDesc')) {
                $searchField->setTitle('Search Description');
                $fields->addFieldToTab('Root.Search', $searchField);
            }

            $heroFieldNames = ['HeroSize', 'HeroTitle'];

            if (! $this->canHaveAHero()) {
                $fields->removeByName($heroFieldNames, true);
                return $fields;
            }

            // High complexity array iteration
            $heroTabFields = [];

            if ($heroSizeField = $fetchField('HeroSize')) {
                $heroSizeOptions = ['Large' => 'Large', 'Medium' => 'Medium'];
                if ($heroSizeField instanceof DropdownField) {
                    $heroSizeField->setSource($heroSizeOptions);
                } else {
                    $heroSizeField = DropdownField::create('HeroSize', 'Hero Size', $heroSizeOptions, $this->HeroSize);
                }
                $heroSizeField->setTitle('Hero Size');
                $heroTabFields[] = $heroSizeField;
            }

            $smallHeroFields = [];
            foreach (['HeroTitle' => 'Hero Title'] as $fieldName => $title) {
                if ($field = $fetchField($fieldName)) {
                    $field->setTitle($title);
                    $smallHeroFields[] = $field;
                }
            }

            $smallHeroFields[] = HeaderField::create('Overlay', 'Overlay settings');

            if ($smallHeroFields !== []) {
                $heroTabFields[] = Wrapper::create(...$smallHeroFields)
                    ->displayIf('HeroSize')->isEqualTo('Small')->end();
            }

            $nonSmallFields = [];

            if ($displayArrowField = $fetchField('HeroDisplayArrow')) {
                $displayArrowField->setTitle('Display scroll arrow?');
                $nonSmallFields[] = $displayArrowField;
            }

            if ($nonSmallFields !== []) {
                $heroTabFields[] = Wrapper::create(...$nonSmallFields)
                    ->displayIf('HeroSize')->isNotEqualTo('Small')->end();
            }

            // THIS MUST REMAIN AS addFieldsToTab
            if ($heroTabFields !== []) {
                $fields->addFieldsToTab('Root.Hero', $heroTabFields);
            }

            return $fields;
        }

        private function getExtraFields(): array { return []; }
        private function getSingleField(): TextField { return TextField::create('Single'); }
        private function getMixedData() { return null; }
        private function getFieldsSomehow() { return new FieldList(); }
        private function canHaveAHero() { return true; }
    }
}
