<?php

namespace SilverStripe\Forms {
    class FormField {}
}

namespace App\Forms {
    use SilverStripe\Forms\FormField;

    class UpperCaseField extends FormField
    {
        public function Value()
        {
            return 'upper';
        }
    }

    class LowerCaseField extends FormField
    {
        public function value()
        {
            return 'lower';
        }
    }

    class FieldCaller
    {
        public function printValues(UpperCaseField $upper, LowerCaseField $lower)
        {
            return $upper->Value() . ' and ' . $lower->value();
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

    class UpperCaseField extends FormField
    {
        public function getValue()
        {
            return 'upper';
        }
    }

    class LowerCaseField extends FormField
    {
        public function getValue()
        {
            return 'lower';
        }
    }

    class FieldCaller
    {
        public function printValues(UpperCaseField $upper, LowerCaseField $lower)
        {
            return $upper->getValue() . ' and ' . $lower->getValue();
        }
    }
}
