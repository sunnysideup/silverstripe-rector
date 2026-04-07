<?php

namespace SilverStripe\ORM\FieldType {
    class DBVarchar {}
}

namespace App\Fields {
    use SilverStripe\ORM\FieldType\DBVarchar;

    class MyCustomVarchar extends DBVarchar
    {
        // Testing uppercase
        public function URL()
        {
            return 'https://example.com';
        }
    }

    class AnotherCustomVarchar extends DBVarchar
    {
        // Testing lowercase
        public function url()
        {
            return 'https://test.com';
        }
    }
}
-----
<?php

namespace SilverStripe\ORM\FieldType {
    class DBVarchar {}
}

namespace App\Fields {
    use SilverStripe\ORM\FieldType\DBVarchar;

    class MyCustomVarchar extends DBVarchar
    {
        // Testing uppercase
        public function URL(): ?string
        {
            return 'https://example.com';
        }
    }

    class AnotherCustomVarchar extends DBVarchar
    {
        // Testing lowercase
        public function url(): ?string
        {
            return 'https://test.com';
        }
    }
}