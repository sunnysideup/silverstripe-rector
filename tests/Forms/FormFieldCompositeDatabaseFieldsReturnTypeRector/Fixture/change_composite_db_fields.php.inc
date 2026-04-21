<?php

namespace App\Forms {
    use SilverStripe\Forms\FormField;

    class StandardField extends FormField {
        public function compositeDatabaseFields() {
            return ['SubField' => 'Varchar'];
        }
    }

    class ProtectedField extends FormField {
        protected function compositeDatabaseFields() {
            return ['Amount' => 'Currency'];
        }
    }

    class PrivateField extends FormField {
        private function compositeDatabaseFields() {
            return ['Code' => 'Int'];
        }
    }

    class MissingReturnField extends FormField {
        public function compositeDatabaseFields() {
            // Did nothing here
        }
    }

    class AlreadyCorrectField extends FormField {
        public function compositeDatabaseFields(): array {
            return ['Already' => 'Correct'];
        }
    }

    class IncorrectTypeField extends FormField {
        public function compositeDatabaseFields(): string {
            return ['Changed' => 'Type'];
        }
    }

    class NonFormField {
        protected function compositeDatabaseFields() {
            return 'Not a FormField';
        }
    }
}
-----
<?php

namespace App\Forms {
    use SilverStripe\Forms\FormField;

    class StandardField extends FormField {
        public function compositeDatabaseFields(): array {
            return ['SubField' => 'Varchar'];
        }
    }

    class ProtectedField extends FormField {
        public function compositeDatabaseFields(): array {
            return ['Amount' => 'Currency'];
        }
    }

    class PrivateField extends FormField {
        public function compositeDatabaseFields(): array {
            return ['Code' => 'Int'];
        }
    }

    class MissingReturnField extends FormField {
        public function compositeDatabaseFields(): array {
            // Did nothing here
            return [];
        }
    }

    class AlreadyCorrectField extends FormField {
        public function compositeDatabaseFields(): array {
            return ['Already' => 'Correct'];
        }
    }

    class IncorrectTypeField extends FormField {
        public function compositeDatabaseFields(): array {
            return ['Changed' => 'Type'];
        }
    }

    class NonFormField {
        protected function compositeDatabaseFields() {
            return 'Not a FormField';
        }
    }
}
