<?php

namespace SilverStripe\Forms {
    class FormField {}
}

namespace App\Forms {
    use SilverStripe\Forms\FormField;

    class MyField extends FormField
    {
        // Edge case 1: Using $valid as variable name (should work correctly with rector)
        public function validate($validator)
        {
            $valid = parent::validate($validator);
            if (!$valid) {
                $validator->validationError($this->Name, 'Invalid', 'validation');
            }
            return $valid;
        }
    }

    class MyField2 extends FormField
    {
        // Edge case 2: Multiple calls to validationError
        public function validate($val)
        {
            $valid = parent::validate($val);
            $val->validationError('field1', 'Error 1', 'required');
            $val->validationError('field2', 'Error 2', 'required');
            return false;
        }
    }

    class MyField3 extends FormField
    {
        // Edge case 3: No calls to validationError - just change signature
        public function validate($validator)
        {
            return parent::validate($validator);
        }
    }
}
-----
<?php

namespace SilverStripe\Forms {
    class FormField {}
}

namespace App\Forms {
    use SilverStripe\Forms\FormField;

    class MyField extends FormField
    {
        // Edge case 1: Using $valid as variable name (should work correctly with rector)
        public function validate(): \SilverStripe\ORM\ValidationResult
        {
            $valid = parent::validate();
            if (!$valid) {
                $valid->addError('Invalid', 'validation');
            }
            return $valid;
        }
    }

    class MyField2 extends FormField
    {
        // Edge case 2: Multiple calls to validationError
        public function validate(): \SilverStripe\ORM\ValidationResult
        {
            $valid = parent::validate();
            $valid->addError('Error 1', 'required');
            $valid->addError('Error 2', 'required');
            return false;
        }
    }

    class MyField3 extends FormField
    {
        // Edge case 3: No calls to validationError - just change signature
        public function validate(): \SilverStripe\ORM\ValidationResult
        {
            return parent::validate();
        }
    }
}