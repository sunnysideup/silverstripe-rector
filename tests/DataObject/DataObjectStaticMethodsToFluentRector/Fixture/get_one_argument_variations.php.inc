<?php

namespace App {
    use SilverStripe\ORM\DataObject;
    use SilverStripe\Security\Member;

    class MyObject extends DataObject {
        public function testVariations() {
            // 1 Argument with self::class
            $a = DataObject::get_one(self::class);
            
            // 2 Arguments with Member::class
            $b = DataObject::get_one(Member::class, ['ID' => 1]);
            
            // 3 Arguments
            $c = DataObject::get_one(Member::class, ['ID' => 2], false);
            
            // 4 Arguments
            $d = DataObject::get_one(Member::class, ['ID' => 3], true, 'Title ASC');
            
            // Static keyword
            $e = DataObject::get_one(static::class, null, true, ['Title' => 'DESC']);

            // delete_by_id
            DataObject::delete_by_id(self::class, 99);
        }
    }
}
-----
<?php

namespace App {
    use SilverStripe\ORM\DataObject;
    use SilverStripe\Security\Member;

    class MyObject extends DataObject {
        public function testVariations() {
            // 1 Argument with self::class
            $a = self::get()->setUseCache(true)->first();
            
            // 2 Arguments with Member::class
            $b = Member::get()->setUseCache(true)->filter(['ID' => 1])->first();
            
            // 3 Arguments
            $c = Member::get()->setUseCache(false)->filter(['ID' => 2])->first();
            
            // 4 Arguments
            $d = Member::get()->setUseCache(true)->filter(['ID' => 3])->sort('Title ASC')->first();
            
            // Static keyword
            $e = static::get()->setUseCache(true)->sort(['Title' => 'DESC'])->first();

            // delete_by_id
            self::get()->setUseCache(true)->byID(99)->delete();
        }
    }
}
