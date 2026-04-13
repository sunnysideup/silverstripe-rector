<?php

namespace SilverStripe\ORM\FieldType {
    class DBField {
        public function saveInto($dataObject) {}
    }
}

namespace App {
    use SilverStripe\ORM\FieldType\DBField;

    class MyCustomField extends DBField
    {
        public function saveInto($dataObject)
        {
            // do save logic here
        }
    }

    class UnrelatedClass
    {
        // This should NOT be modified because it doesn't extend DBField
        public function saveInto($dataObject)
        {
            // not a DBField
        }
    }
}
-----
<?php

namespace SilverStripe\ORM\FieldType {
    class DBField {
        public function saveInto($dataObject): void {}
    }
}

namespace App {
    use SilverStripe\ORM\FieldType\DBField;

    class MyCustomField extends DBField
    {
        public function saveInto($dataObject): void
        {
            // do save logic here
        }
    }

    class UnrelatedClass
    {
        // This should NOT be modified because it doesn't extend DBField
        public function saveInto($dataObject)
        {
            // not a DBField
        }
    }
}
