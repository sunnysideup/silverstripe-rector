<?php

namespace App {
    use SilverStripe\ORM\DataObject;
    use SilverStripe\Security\Member;

    class MyObject extends DataObject {
        public function testEdgeCases($className) {
            // Edge case 1: null filter (should NOT add filter() call)
            $a = DataObject::get_one(Member::class, null);

            // Edge case 2: empty array filter
            $b = DataObject::get_one(Member::class, []);

            // Edge case 3: string sort (should become sort('Title ASC'))
            $c = DataObject::get_one(Member::class, ['ID' => 1], true, 'Title ASC');

            // Edge case 4: array sort
            $d = DataObject::get_one(Member::class, ['ID' => 2], true, ['Title' => 'DESC', 'Name' => 'ASC']);

            // Edge case 5: false cache (should NOT call setUseCache)
            $e = DataObject::get_one(Member::class, ['ID' => 3], false);

            // Edge case 6: No optional arguments at all
            $f = DataObject::get_one(Member::class);

            // Edge case 7: Only filter argument
            $g = DataObject::get_one(Member::class, ['Email' => 'test@example.org']);

            // Edge case 8: Only filter and cache arguments
            $h = DataObject::get_one(Member::class, ['Email' => 'test2@example.org'], true);

            // Edge case 9: Variable as class reference (can't be transformed - stays as-is)
            $i = DataObject::get_one($className);
        }
    }
}
-----
<?php

namespace App {
    use SilverStripe\ORM\DataObject;
    use SilverStripe\Security\Member;

    class MyObject extends DataObject {
        public function testEdgeCases($className) {
            // Edge case 1: null filter - should NOT add filter()
            $a = Member::get()->setUseCache(true)->first();

            // Edge case 2: empty array filter - filter() not added
            $b = Member::get()->setUseCache(true)->first();

            // Edge case 3: string sort
            $c = Member::get()->setUseCache(true)->filter(['ID' => 1])->sort('Title ASC')->first();

            // Edge case 4: array sort  
            $d = Member::get()->setUseCache(true)->filter(['ID' => 2])->sort(['Title' => 'DESC', 'Name' => 'ASC'])->first();

            // Edge case 5: false cache 
            $e = Member::get()->setUseCache(false)->filter(['ID' => 3])->first();

            // Edge case 6: No optional arguments
            $f = Member::get()->setUseCache(true)->first();

            // Edge case 7: Only filter argument
            $g = Member::get()->setUseCache(true)->filter(['Email' => 'test@example.org'])->first();

            // Edge case 8: Only filter and cache arguments
            $h = Member::get()->setUseCache(true)->filter(['Email' => 'test2@example.org'])->first();

            // Edge case 9: Variable - stays unchanged (can't statically transform)
            $i = DataObject::get_one($className);
        }
    }
}