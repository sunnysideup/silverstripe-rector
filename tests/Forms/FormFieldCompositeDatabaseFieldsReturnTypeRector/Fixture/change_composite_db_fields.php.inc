<?php

namespace SilverStripe\Forms {
    class FormField {}
}

namespace App\Forms {
    class StandardField extends \SilverStripe\Forms\FormField {
        public function compositeDatabaseFields() {
            return ['SubField' => 'Varchar'];
        }
    }

    class ProtectedField extends \SilverStripe\Forms\FormField {
        protected function compositeDatabaseFields() {
            return ['Amount' => 'Currency'];
        }
    }

    class PrivateField extends \SilverStripe\Forms\FormField {
        private function compositeDatabaseFields() {
            return ['Code' => 'Int'];
        }
    }

    class MissingReturnField extends \SilverStripe\Forms\FormField {
        public function compositeDatabaseFields() {
            // Did nothing here
        }
    }

    class AlreadyCorrectField extends \SilverStripe\Forms\FormField {
        public function compositeDatabaseFields(): array {
            return ['Already' => 'Correct'];
        }
    }

    class IncorrectTypeField extends \SilverStripe\Forms\FormField {
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

namespace SilverStripe\Forms {
    class FormField {}
}

namespace App\Forms {
    class StandardField extends \SilverStripe\Forms\FormField {
        public function compositeDatabaseFields(): array {
            return ['SubField' => 'Varchar'];
        }
    }

    class ProtectedField extends \SilverStripe\Forms\FormField {
        public function compositeDatabaseFields(): array {
            return ['Amount' => 'Currency'];
        }
    }

    class PrivateField extends \SilverStripe\Forms\FormField {
        public function compositeDatabaseFields(): array {
            return ['Code' => 'Int'];
        }
    }

    class MissingReturnField extends \SilverStripe\Forms\FormField {
        public function compositeDatabaseFields(): array {
            // Did nothing here
            return [];
        }
    }

    class AlreadyCorrectField extends \SilverStripe\Forms\FormField {
        public function compositeDatabaseFields(): array {
            return ['Already' => 'Correct'];
        }
    }

    class IncorrectTypeField extends \SilverStripe\Forms\FormField {
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
