<?php

namespace SilverStripe\Model;

// Dummy class to satisfy PHPStan's inheritance check during the test
class ModelData {}

class MyModel extends ModelData
{
    public function exists()
    {
        return true;
    }
}

class MyOtherModel extends ModelData
{
    protected function exists()
    {
        return true;
    }
}
-----
<?php

namespace SilverStripe\Model;

// Dummy class to satisfy PHPStan's inheritance check during the test
class ModelData {}

class MyModel extends ModelData
{
    public function exists(): bool
    {
        return true;
    }
}

class MyOtherModel extends ModelData
{
    public function exists(): bool
    {
        return true;
    }
}