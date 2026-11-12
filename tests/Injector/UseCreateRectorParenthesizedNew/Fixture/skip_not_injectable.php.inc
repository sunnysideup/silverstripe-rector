<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Injector\UseCreateRectorParenthesizedNew\Fixture;

class SomeOtherClass
{
    public function run()
    {
        $foo = (new \stdClass())->foo;
    }
}
?>
