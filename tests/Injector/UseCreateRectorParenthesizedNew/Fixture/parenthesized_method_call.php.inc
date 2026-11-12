<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Injector\UseCreateRectorParenthesizedNew\Fixture;

use Netwerkstatt\SilverstripeRector\Tests\Injector\UseCreateRector\Source\InjectableClass;

class SomeClass
{
    public function run($fields)
    {
        $fields->addFieldToTab('Root.Main', (new InjectableClass('Name', 'Title'))->setDescription('Some text'));
        $chained = (new InjectableClass('Name'))->setTitle('Title')->setDescription('Some text');
    }
}
?>
-----
<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Injector\UseCreateRectorParenthesizedNew\Fixture;

use Netwerkstatt\SilverstripeRector\Tests\Injector\UseCreateRector\Source\InjectableClass;

class SomeClass
{
    public function run($fields)
    {
        $fields->addFieldToTab('Root.Main', \Netwerkstatt\SilverstripeRector\Tests\Injector\UseCreateRector\Source\InjectableClass::create('Name', 'Title')->setDescription('Some text'));
        $chained = \Netwerkstatt\SilverstripeRector\Tests\Injector\UseCreateRector\Source\InjectableClass::create('Name')->setTitle('Title')->setDescription('Some text');
    }
}
?>
