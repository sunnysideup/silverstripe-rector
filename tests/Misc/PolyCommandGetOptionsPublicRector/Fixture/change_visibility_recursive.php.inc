<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Misc\PolyCommandGetOptionsPublicRector\Fixture;

// Dummy classes to satisfy PHPStan's inheritance check
namespace SilverStripe\PolyExecution {
    class PolyCommand {}
}

namespace App\Commands {
    use SilverStripe\PolyExecution\PolyCommand;

    // Intermediate class
    abstract class AbstractCustomCommand extends PolyCommand {}

    // The target class extending the intermediate class
    class MyDeepCommand extends AbstractCustomCommand
    {
        protected function getOptions(): array
        {
            return ['option2' => false];
        }
    }
}
-----
<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Misc\PolyCommandGetOptionsPublicRector\Fixture;

// Dummy classes to satisfy PHPStan's inheritance check
namespace SilverStripe\PolyExecution {
    class PolyCommand {}
}

namespace App\Commands {
    use SilverStripe\PolyExecution\PolyCommand;

    // Intermediate class
    abstract class AbstractCustomCommand extends PolyCommand {}

    // The target class extending the intermediate class
    class MyDeepCommand extends AbstractCustomCommand
    {
        public function getOptions(): array
        {
            return ['option2' => false];
        }
    }
}