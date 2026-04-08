<?php

namespace SilverStripe\ORM\FieldType {
    class DBField {}
}

namespace SilverStripe\Forms {
    class FormField {}
}

namespace App\Fields {
    use SilverStripe\ORM\FieldType\DBField;

    class MyCustomDBField extends DBField
    {
        public function scaffoldFormField($title = null, $params = null)
        {
            return null;
        }
    }
    
    class AnotherCustomDBField extends DBField
    {
        protected function scaffoldFormField()
        {
            return null;
        }
    }
}
-----
<?php

namespace SilverStripe\ORM\FieldType {
    class DBField {}
}

namespace SilverStripe\Forms {
    class FormField {}
}

namespace App\Fields {
    use SilverStripe\ORM\FieldType\DBField;

    class MyCustomDBField extends DBField
    {
        public function scaffoldFormField(?string $title = null, array $params = []): ?\SilverStripe\Forms\FormField
        {
            return null;
        }
    }
    
    class AnotherCustomDBField extends DBField
    {
        public function scaffoldFormField(?string $title = null, array $params = []): ?\SilverStripe\Forms\FormField
        {
            return null;
        }
    }
}
