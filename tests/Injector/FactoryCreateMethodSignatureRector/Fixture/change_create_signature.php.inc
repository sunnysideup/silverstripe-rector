<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Injector\FactoryCreateMethodSignatureRector\Fixture;

// Dummy interfaces and classes to satisfy PHPStan's inheritance checks
namespace SilverStripe\Core\Injector {
    interface Factory {}
}

namespace App\Factories {
    use SilverStripe\Core\Injector\Factory;

    // Intermediate class that implements the interface
    abstract class AbstractFactory implements Factory {}

    // Target class 1: Direct implementation, completely wrong signature
    class MyBadFactory implements Factory
    {
        protected function create($service, $config)
        {
            return new \stdClass();
        }
    }

    // Target class 2: Inherits interface implementation, missing return type
    class InheritedFactory extends AbstractFactory
    {
        public function create($service, array $params = [])
        {
            return new \stdClass();
        }
    }
}
-----
<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Injector\FactoryCreateMethodSignatureRector\Fixture;

// Dummy interfaces and classes to satisfy PHPStan's inheritance checks
namespace SilverStripe\Core\Injector {
    interface Factory {}
}

namespace App\Factories {
    use SilverStripe\Core\Injector\Factory;

    // Intermediate class that implements the interface
    abstract class AbstractFactory implements Factory {}

    // Target class 1: Direct implementation, completely wrong signature
    class MyBadFactory implements Factory
    {
        public function create($service, array $params = []): ?object
        {
            return new \stdClass();
        }
    }

    // Target class 2: Inherits interface implementation, missing return type
    class InheritedFactory extends AbstractFactory
    {
        public function create($service, array $params = []): ?object
        {
            return new \stdClass();
        }
    }
}