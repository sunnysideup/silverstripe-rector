<?php

namespace SilverStripe\ORM\FieldType {
    class DBVarchar {}
}

namespace App\Fields {
    use SilverStripe\ORM\FieldType\DBVarchar;

    class MyCustomVarchar extends DBVarchar
    {
        public function URL()
        {
            return 'https://example.com';
        }
        
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
        public function URL(): ?string
        {
            return 'https://example.com';
        }
        
        public function url(): ?string
        {
            return 'https://test.com';
        }
    }
}
