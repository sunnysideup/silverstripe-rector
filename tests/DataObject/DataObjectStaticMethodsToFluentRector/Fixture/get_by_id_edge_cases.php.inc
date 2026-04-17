<?php

namespace App {
    use SilverStripe\ORM\DataObject;
    use SilverStripe\Security\Member;

    class MyObject extends DataObject {
        public function testGetByIdEdgeCases($idVariable, $className) {
            // Standard case with numeric ID
            $a = DataObject::get_by_id(Member::class, 1);

            // String literal ID
            $b = DataObject::get_by_id(Member::class, '2');

            // Variable as ID
            $c = DataObject::get_by_id(Member::class, $idVariable);

            // Variable as class
            $d = DataObject::get_by_id($className, 1);

            // With self::class
            $e = DataObject::get_by_id(self::class, 99);

            // With static::class
            $f = DataObject::get_by_id(static::class, 100);
        }
    }
}
-----
<?php

namespace App {
    use SilverStripe\ORM\DataObject;
    use SilverStripe\Security\Member;

    class MyObject extends DataObject {
        public function testGetByIdEdgeCases($idVariable, $className) {
            // Standard case with numeric ID
            $a = Member::get()->setUseCache(true)->byID(1);

            // String literal ID
            $b = Member::get()->setUseCache(true)->byID('2');

            // Variable as ID
            $c = Member::get()->setUseCache(true)->byID($idVariable);

            // Variable as class
            $d = $className::get()->setUseCache(true)->byID(1);

            // With self::class
            $e = self::get()->setUseCache(true)->byID(99);

            // With static::class
            $f = static::get()->setUseCache(true)->byID(100);
        }
    }
}