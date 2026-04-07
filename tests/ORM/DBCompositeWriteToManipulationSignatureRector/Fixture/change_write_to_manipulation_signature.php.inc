<?php

namespace SilverStripe\ORM\FieldType {
    class DBComposite {}
}

namespace App\Fields {
    use SilverStripe\ORM\FieldType\DBComposite;

    class MyCustomComposite extends DBComposite
    {
        protected function writeToManipulation($manipulation)
        {
            // do something
        }
    }
}
-----
<?php

namespace SilverStripe\ORM\FieldType {
    class DBComposite {}
}

namespace App\Fields {
    use SilverStripe\ORM\FieldType\DBComposite;

    class MyCustomComposite extends DBComposite
    {
        public function writeToManipulation(array &$manipulation): void
        {
            // do something
        }
    }
}
