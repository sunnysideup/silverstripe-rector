<?php

namespace SilverStripe\Forms {
    class FormField {}
}

namespace App\Forms {
    use SilverStripe\Forms\FormField;

    class AlreadyRenamedField extends FormField {
        // Edge case 1: Already renamed method - should skip
        public function getValue() {
            return 'test';
        }
    }

    class DifferentMethodField extends FormField {
        // Edge case 2: Has other methods but not Value/value - should skip
        public function getData() {
            return 'data';
        }
    }

    class MixedField extends FormField {
        // Edge case 3: Only has old method name - should rename
        public function Value() { return 'old'; }
    }
}
-----
<?php

namespace SilverStripe\Forms {
    class FormField {}
}

namespace App\Forms {
    use SilverStripe\Forms\FormField;

    class AlreadyRenamedField extends FormField {
        // Edge case 1: Already renamed method - should skip
        public function getValue() {
            return 'test';
        }
    }

    class DifferentMethodField extends FormField {
        // Edge case 2: Has other methods but not Value/value - should skip
        public function getData() {
            return 'data';
        }
    }

    class MixedField extends FormField {
        // Edge case 3: Only has old method name - should rename
        public function getValue() { return 'old'; }
    }
}