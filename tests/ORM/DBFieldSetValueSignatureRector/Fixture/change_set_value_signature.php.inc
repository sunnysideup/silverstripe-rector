<?php

namespace SilverStripe\ORM\FieldType {
    class DBField {}
}

namespace App\Fields {
    use SilverStripe\ORM\FieldType\DBField;
    use SilverStripe\ORM\ModelData;

    class MyCustomDBField extends DBField
    {
        public function setValue($value, $record = null, $markChanged = true)
        {
            return $this;
        }
    }
    
    class AnotherCustomDBField extends DBField
    {
        protected function setValue($myCustomValueVar)
        {
            return $this;
        }
    }
}
-----
<?php

namespace SilverStripe\ORM\FieldType {
    class DBField {}
}

namespace App\Fields {
    use SilverStripe\ORM\FieldType\DBField;
    use SilverStripe\ORM\ModelData;

    class MyCustomDBField extends DBField
    {
        public function setValue(mixed $value, null|array|ModelData $record = null, bool $markChanged = true): static
        {
            return $this;
        }
    }
    
    class AnotherCustomDBField extends DBField
    {
        public function setValue(mixed $myCustomValueVar, null|array|ModelData $record = null, bool $markChanged = true): static
        {
            return $this;
        }
    }
}
