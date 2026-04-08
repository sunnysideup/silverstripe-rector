<?php

namespace SilverStripe\Forms {
    class FormField {}
}

namespace App\Forms {
    use SilverStripe\Forms\FormField;

    class MyCustomField extends FormField
    {
        public function validate($validator)
        {
            $valid = parent::validate($validator);

            if (true) {
                $validator->validationError(
                    $this->name,
                    'That "Slug" is not unique for listings',
                    'validation'
                );
                $valid = false;
            }

            return $valid;
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

    class MyCustomField extends FormField
    {
        public function validate(): \SilverStripe\ORM\ValidationResult
        {
            $valid = parent::validate();

            if (true) {
                $valid->addError('That "Slug" is not unique for listings', 'validation');
            }

            return $valid;
        }
    }
}
