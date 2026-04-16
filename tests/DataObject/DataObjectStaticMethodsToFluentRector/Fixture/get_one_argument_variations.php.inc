<?php

namespace App {
    use SilverStripe\ORM\DataObject;
    use SilverStripe\Security\Member;

    function testGetOneVariations() {
        // 1 Argument
        $a = DataObject::get_one(Member::class);
        
        // 2 Arguments
        $b = DataObject::get_one(Member::class, ['ID' => 1]);
        
        // 3 Arguments
        $c = DataObject::get_one(Member::class, ['ID' => 2], false);
        
        // 4 Arguments (String Sort)
        $d = DataObject::get_one(Member::class, ['ID' => 3], true, 'Title ASC');
        
        // 4 Arguments (Array Sort)
        $e = DataObject::get_one(Member::class, ['ID' => 4], true, ['Title' => 'DESC']);

        // Variable Class check
        $class = Member::class;
        $f = DataObject::get_one($class, null, true, 'ID DESC');
    }
}
-----
<?php

namespace App {
    use SilverStripe\ORM\DataObject;
    use SilverStripe\Security\Member;

    function testGetOneVariations() {
        // 1 Argument
        $a = Member::get()->setUseCache(true)->first();
        
        // 2 Arguments
        $b = Member::get()->setUseCache(true)->filter(['ID' => 1])->first();
        
        // 3 Arguments
        $c = Member::get()->setUseCache(false)->filter(['ID' => 2])->first();
        
        // 4 Arguments (String Sort)
        $d = Member::get()->setUseCache(true)->filter(['ID' => 3])->sort('Title ASC')->first();
        
        // 4 Arguments (Array Sort)
        $e = Member::get()->setUseCache(true)->filter(['ID' => 4])->sort(['Title' => 'DESC'])->first();

        // Variable Class check
        $class = Member::class;
        $f = $class::get()->setUseCache(true)->sort('ID DESC')->first();
    }
}
