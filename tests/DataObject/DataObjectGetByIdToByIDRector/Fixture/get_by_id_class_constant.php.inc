<?php

namespace Netwerkstatt\SilverstripeRector\Tests\DataObject\DataObjectGetByIdToByIDRector\Fixture;

use SilverStripe\ORM\DataObject;

class MyDataObject extends DataObject
{
}

function getByIdTest()
{
    $id = 1;
    $obj1 = DataObject::get_by_id(MyDataObject::class, $id);
}
?>
-----
<?php

namespace Netwerkstatt\SilverstripeRector\Tests\DataObject\DataObjectGetByIdToByIDRector\Fixture;

use SilverStripe\ORM\DataObject;

class MyDataObject extends DataObject
{
}

function getByIdTest()
{
    $id = 1;
    $obj1 = MyDataObject::get()->byID($id);
}
?>
