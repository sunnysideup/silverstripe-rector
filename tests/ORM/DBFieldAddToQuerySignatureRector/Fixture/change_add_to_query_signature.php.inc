<?php

namespace SilverStripe\ORM\FieldType {
    class DBField {}
}

namespace SilverStripe\ORM\Queries {
    class SQLSelect {}
}

namespace App\Fields {
    use SilverStripe\ORM\FieldType\DBField;

    class MyCustomDBField extends DBField
    {
        protected function addToQuery($query)
        {
            // do something
        }
    }
}
-----
<?php

namespace SilverStripe\ORM\FieldType {
    class DBField {}
}

namespace SilverStripe\ORM\Queries {
    class SQLSelect {}
}

namespace App\Fields {
    use SilverStripe\ORM\FieldType\DBField;

    class MyCustomDBField extends DBField
    {
        public function addToQuery(\SilverStripe\ORM\Queries\SQLSelect &$query)
        {
            // do something
        }
    }
}
