<?php

namespace Netwerkstatt\SilverstripeRector\Tests\DataObject\DataObjectStaticMethodsToFluentRector\Fixture;

use SilverStripe\ORM\DataObject;
class MyDataObject extends DataObject
{
}

DataObject::delete_by_id(MyDataObject::class, 123);
?>
-----
<?php

namespace Netwerkstatt\SilverstripeRector\Tests\DataObject\DataObjectStaticMethodsToFluentRector\Fixture;

use SilverStripe\ORM\DataObject;
class MyDataObject extends DataObject
{
}

MyDataObject::get()->setUseCache(true)->byID(123)->delete();
?>
