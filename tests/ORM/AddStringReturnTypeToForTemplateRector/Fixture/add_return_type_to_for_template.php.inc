<?php

namespace SilverStripe\ORM\FieldType {
    class DBField {
        public function forTemplate() {
            return '';
        }
    }
}

namespace App {
    use SilverStripe\ORM\FieldType\DBField;

    class MyCustomField extends DBField
    {
        public function forTemplate()
        {
            return 'formatted output';
        }
    }

    class UnrelatedClass
    {
        // This should NOT be modified because it doesn't extend DBField
        public function forTemplate()
        {
            return 'ignored';
        }
    }
}
-----
<?php

namespace SilverStripe\ORM\FieldType {
    class DBField {
        public function forTemplate(): string {
            return '';
        }
    }
}

namespace App {
    use SilverStripe\ORM\FieldType\DBField;

    class MyCustomField extends DBField
    {
        public function forTemplate(): string
        {
            return 'formatted output';
        }
    }

    class UnrelatedClass
    {
        // This should NOT be modified because it doesn't extend DBField
        public function forTemplate()
        {
            return 'ignored';
        }
    }
}
