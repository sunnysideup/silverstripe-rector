<?php

namespace Netwerkstatt\SilverstripeRector\Tests\DataObject\DataObjectStaticMethodsToFluentRector\Fixture;

use SilverStripe\ORM\DataObject;

class MyDataObject extends DataObject
{
}

$id = 123;
$obj = DataObject::get_by_id(MyDataObject::class, $id);
?>
-----
<?php

namespace Netwerkstatt\SilverstripeRector\Tests\DataObject\DataObjectStaticMethodsToFluentRector\Fixture;

use SilverStripe\ORM\DataObject;

class MyDataObject extends DataObject
{
}

$id = 123;
$obj = MyDataObject::get()->setUseCache(true)->byID($id);
?>
