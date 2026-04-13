<?php

namespace SilverStripe\ORM\FieldType {
    class DBString {
        public function prepValueForDB(mixed $value): array|string|null { return null; }
    }
}

namespace App {
    use SilverStripe\ORM\FieldType\DBString;

    class EmailAddress extends DBString
    {
        public function prepValueForDB(mixed $value): mixed
        {
            return $value;
        }
    }

    class AnotherString extends DBString
    {
        public function prepValueForDB(mixed $value)
        {
            return $value;
        }
    }

    class UnrelatedClass
    {
        // This should NOT be modified because it doesn't extend DBString
        public function prepValueForDB(mixed $value): mixed
        {
            return $value;
        }
    }
}
-----
<?php

namespace SilverStripe\ORM\FieldType {
    class DBString {
        public function prepValueForDB(mixed $value): array|string|null { return null; }
    }
}

namespace App {
    use SilverStripe\ORM\FieldType\DBString;

    class EmailAddress extends DBString
    {
        public function prepValueForDB(mixed $value): array|string|null
        {
            return $value;
        }
    }

    class AnotherString extends DBString
    {
        public function prepValueForDB(mixed $value): array|string|null
        {
            return $value;
        }
    }

    class UnrelatedClass
    {
        // This should NOT be modified because it doesn't extend DBString
        public function prepValueForDB(mixed $value): mixed
        {
            return $value;
        }
    }
}
