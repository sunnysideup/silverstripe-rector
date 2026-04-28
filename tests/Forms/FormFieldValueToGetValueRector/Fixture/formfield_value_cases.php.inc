<?php

namespace SilverStripe\Forms {
    if (!class_exists(FormField::class)) {
        class FormField {
            public function getValue(): mixed { return null; }
        }
    }
}

namespace App\Forms {
    use SilverStripe\Forms\FormField;

    // Case 1: Standard uppercase Value() untyped
    class StandardField extends FormField {
        public function Value() {
            return 'Standard';
        }
    }

    // Case 2: Lowercase value() untyped
    class LowercaseField extends FormField {
        public function value() {
            return 'Lowercase';
        }
    }

    // Case 3: Protected visibility must become public
    class ProtectedField extends FormField {
        protected function Value() {
            return 'Protected';
        }
    }

    // Case 4: Private visibility must become public
    class PrivateField extends FormField {
        private function value() {
            return 'Private';
        }
    }

    // Case 5: Already named getValue but missing return type
    class MissingTypeField extends FormField {
        public function getValue() {
            return 'Missing Type';
        }
    }

    // Case 6: Has a different return type (e.g. string) -> override to mixed
    class WrongTypeField extends FormField {
        public function Value(): string {
            return 'Wrong Type';
        }
    }

    // Case 7: Nullable return type -> override to mixed
    class NullableTypeField extends FormField {
        public function Value(): ?string {
            return 'Nullable';
        }
    }

    // Case 8: Union return type -> override to mixed
    class UnionTypeField extends FormField {
        public function value(): string|int {
            return 123;
        }
    }

    // Case 9: DocBlock only -> still needs mixed return type injected
    class DocblockField extends FormField {
        /**
         * @return string
         */
        public function Value() {
            return 'Docblock';
        }
    }

    // Case 10: Incompletely upgraded getValue (wrong type and protected)
    class IncompleteUpgradeField extends FormField {
        protected function getValue(): string {
            return 'Incomplete';
        }
    }

    class FieldCaller {
        public function runCalls(FormField $field, ?FormField $nullableField) {
            // Case 11: Standard method calls
            $a = $field->Value();
            $b = $field->value();
            
            // Case 12: Nullsafe operator
            $c = $nullableField?->Value();
            $d = $nullableField?->value();

            // Case 13: Call inside an array
            $arr = ['val' => $field->Value()];

            // Case 14: Call inside a conditional
            if ($field->value() === 'test') {
                echo "yes";
            }

            // Case 15: Nested method calls
            $this->process($field->Value());
        }

        public function process(mixed $val) {}
    }

    // Case 16: Multi-level inheritance
    class InheritedField extends StandardField {
        protected function value(): array {
            return [];
        }
    }

    // Case 17: Unrelated class method should not be touched
    class NotAFormField {
        protected function Value(): string {
            return 'Ignore Me';
        }
    }
    
    // Case 18: Unrelated method call should not be touched
    class UnrelatedCaller {
        public function runCalls(NotAFormField $notField) {
            $x = $notField->Value();
            $y = $notField?->Value();
        }
    }
}
-----
<?php

namespace SilverStripe\Forms {
    if (!class_exists(FormField::class)) {
        class FormField {
            public function getValue(): mixed { return null; }
        }
    }
}

namespace App\Forms {
    use SilverStripe\Forms\FormField;

    // Case 1: Standard uppercase Value() untyped
    class StandardField extends FormField {
        public function getValue(): mixed {
            return 'Standard';
        }
    }

    // Case 2: Lowercase value() untyped
    class LowercaseField extends FormField {
        public function getValue(): mixed {
            return 'Lowercase';
        }
    }

    // Case 3: Protected visibility must become public
    class ProtectedField extends FormField {
        public function getValue(): mixed {
            return 'Protected';
        }
    }

    // Case 4: Private visibility must become public
    class PrivateField extends FormField {
        public function getValue(): mixed {
            return 'Private';
        }
    }

    // Case 5: Already named getValue but missing return type
    class MissingTypeField extends FormField {
        public function getValue(): mixed {
            return 'Missing Type';
        }
    }

    // Case 6: Has a different return type (e.g. string) -> override to mixed
    class WrongTypeField extends FormField {
        public function getValue(): mixed {
            return 'Wrong Type';
        }
    }

    // Case 7: Nullable return type -> override to mixed
    class NullableTypeField extends FormField {
        public function getValue(): mixed {
            return 'Nullable';
        }
    }

    // Case 8: Union return type -> override to mixed
    class UnionTypeField extends FormField {
        public function getValue(): mixed {
            return 123;
        }
    }

    // Case 9: DocBlock only -> still needs mixed return type injected
    class DocblockField extends FormField {
        /**
         * @return string
         */
        public function getValue(): mixed {
            return 'Docblock';
        }
    }

    // Case 10: Incompletely upgraded getValue (wrong type and protected)
    class IncompleteUpgradeField extends FormField {
        public function getValue(): mixed {
            return 'Incomplete';
        }
    }

    class FieldCaller {
        public function runCalls(FormField $field, ?FormField $nullableField) {
            // Case 11: Standard method calls
            $a = $field->getValue();
            $b = $field->getValue();
            
            // Case 12: Nullsafe operator
            $c = $nullableField?->getValue();
            $d = $nullableField?->getValue();

            // Case 13: Call inside an array
            $arr = ['val' => $field->getValue()];

            // Case 14: Call inside a conditional
            if ($field->getValue() === 'test') {
                echo "yes";
            }

            // Case 15: Nested method calls
            $this->process($field->getValue());
        }

        public function process(mixed $val) {}
    }

    // Case 16: Multi-level inheritance
    class InheritedField extends StandardField {
        public function getValue(): mixed {
            return [];
        }
    }

    // Case 17: Unrelated class method should not be touched
    class NotAFormField {
        protected function Value(): string {
            return 'Ignore Me';
        }
    }
    
    // Case 18: Unrelated method call should not be touched
    class UnrelatedCaller {
        public function runCalls(NotAFormField $notField) {
            $x = $notField->Value();
            $y = $notField?->Value();
        }
    }
}
