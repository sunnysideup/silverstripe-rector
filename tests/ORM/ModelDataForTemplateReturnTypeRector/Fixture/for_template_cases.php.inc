<?php

namespace App\Models {
    use SilverStripe\ORM\FieldType\DBField;
    use SilverStripe\ORM\DataObject;
    use SilverStripe\View\ViewableData;

    // Case 1: DBField missing return type
    class CustomField extends DBField {
        public function forTemplate() {
            return 'CustomField';
        }
    }

    // Case 2: DataObject missing return type
    class MyObject extends DataObject {
        public function forTemplate() {
            return $this->Title;
        }
    }

    // Case 3: Direct ViewableData subclass missing return type
    class CustomModel extends ViewableData {
        public function forTemplate() {
            return 'Model';
        }
    }

    // Case 4: Already correct (idempotency check)
    class CorrectField extends DBField {
        public function forTemplate(): string {
            return 'Correct';
        }
    }

    // Case 5: Has DocBlock but no actual return type
    class DocBlockField extends DBField {
        /**
         * @return string
         */
        public function forTemplate() {
            return 'DocBlock';
        }
    }

    // Case 6: Has nullable return type (must be strictly string in SS6)
    class NullableField extends DBField {
        public function forTemplate(): ?string {
            return 'Nullable';
        }
    }

    // Case 7: Unrelated class with same method name
    class NotSilverstripe {
        public function forTemplate() {
            return 'Should remain untouched';
        }
    }

    // Case 8: Deep inheritance
    class DeepField extends CustomField {
        public function forTemplate() {
            return 'Deep Content';
        }
    }

    // Case 9: Abstract class definition
    abstract class AbstractModel extends ViewableData {
        abstract public function forTemplate();
    }

    // Case 10: Completely wrong return type (e.g. array)
    class WrongTypeField extends DBField {
        public function forTemplate(): array {
            return ['Wrong'];
        }
    }

    // Case 11: Union return type
    class UnionTypeField extends DBField {
        public function forTemplate(): string|int {
            return 123;
        }
    }

    // Case 12: Method with parameters (edge case)
    class ParamField extends DBField {
        public function forTemplate($arg = null) {
            return $arg ? 'yes' : 'no';
        }
    }

    // Case 13: Protected visibility
    class ProtectedModel extends ViewableData {
        protected function forTemplate() {
            return 'protected';
        }
    }

    // Case 14: Private visibility
    class PrivateModel extends ViewableData {
        private function forTemplate() {
            return 'private';
        }
    }
}
-----
<?php

namespace App\Models {
    use SilverStripe\ORM\FieldType\DBField;
    use SilverStripe\ORM\DataObject;
    use SilverStripe\View\ViewableData;

    // Case 1: DBField missing return type
    class CustomField extends DBField {
        public function forTemplate(): string {
            return 'CustomField';
        }
    }

    // Case 2: DataObject missing return type
    class MyObject extends DataObject {
        public function forTemplate(): string {
            return $this->Title;
        }
    }

    // Case 3: Direct ViewableData subclass missing return type
    class CustomModel extends ViewableData {
        public function forTemplate(): string {
            return 'Model';
        }
    }

    // Case 4: Already correct (idempotency check)
    class CorrectField extends DBField {
        public function forTemplate(): string {
            return 'Correct';
        }
    }

    // Case 5: Has DocBlock but no actual return type
    class DocBlockField extends DBField {
        /**
         * @return string
         */
        public function forTemplate(): string {
            return 'DocBlock';
        }
    }

    // Case 6: Has nullable return type (must be strictly string in SS6)
    class NullableField extends DBField {
        public function forTemplate(): string {
            return 'Nullable';
        }
    }

    // Case 7: Unrelated class with same method name
    class NotSilverstripe {
        public function forTemplate() {
            return 'Should remain untouched';
        }
    }

    // Case 8: Deep inheritance
    class DeepField extends CustomField {
        public function forTemplate(): string {
            return 'Deep Content';
        }
    }

    // Case 9: Abstract class definition
    abstract class AbstractModel extends ViewableData {
        abstract public function forTemplate(): string;
    }

    // Case 10: Completely wrong return type (e.g. array)
    class WrongTypeField extends DBField {
        public function forTemplate(): string {
            return ['Wrong'];
        }
    }

    // Case 11: Union return type
    class UnionTypeField extends DBField {
        public function forTemplate(): string {
            return 123;
        }
    }

    // Case 12: Method with parameters (edge case)
    class ParamField extends DBField {
        public function forTemplate($arg = null): string {
            return $arg ? 'yes' : 'no';
        }
    }

    // Case 13: Protected visibility
    class ProtectedModel extends ViewableData {
        protected function forTemplate(): string {
            return 'protected';
        }
    }

    // Case 14: Private visibility
    class PrivateModel extends ViewableData {
        private function forTemplate(): string {
            return 'private';
        }
    }
}
