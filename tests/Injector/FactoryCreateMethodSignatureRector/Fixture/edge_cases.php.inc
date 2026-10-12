<?php


namespace SilverStripe\Core\Injector {
    interface Factory {}
}

namespace App\Factories {
    use SilverStripe\Core\Injector\Factory;

    // Edge case 1: Already correct signature - should skip
    class CorrectFactory implements Factory {
        public function create($service, array $params = []): ?object {
            return new \stdClass();
        }
    }

    // Edge case 2: No create method at all - should skip
    class NoMethodFactory implements Factory {
    }

    // Edge case 3: Has create but wrong parameter name
    class WrongParamFactory implements Factory {
        public function create($svc, array $args = []): ?object {
            return new \stdClass();
        }
    }

    // Edge case 4: Empty params default - should keep as []
    class EmptyParamsFactory implements Factory {
        public function create($service, array $params = []) {
            return new \stdClass();
        }
    }
}
-----
<?php


namespace SilverStripe\Core\Injector {
    interface Factory {}
}

namespace App\Factories {
    use SilverStripe\Core\Injector\Factory;

    // Edge case 1: Already correct signature - should skip
    class CorrectFactory implements Factory {
        public function create($service, array $params = []): ?object {
            return new \stdClass();
        }
    }

    // Edge case 2: No create method at all - should skip
    class NoMethodFactory implements Factory {}

    // Edge case 3: Has create but wrong parameter name
    class WrongParamFactory implements Factory {
        public function create($service, array $params = []): ?object {
            return new \stdClass();
        }
    }

    // Edge case 4: Empty params default - should keep as []
    class EmptyParamsFactory implements Factory {
        public function create($service, array $params = []): ?object {
            return new \stdClass();
        }
    }
}