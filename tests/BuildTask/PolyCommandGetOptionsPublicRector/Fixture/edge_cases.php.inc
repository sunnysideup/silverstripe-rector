<?php

namespace SilverStripe\PolyExecution {
    class PolyCommand {
        public function getOptions() {
            return [];
        }
    }
}

namespace App\Commands {
    use SilverStripe\PolyExecution\PolyCommand;

    class MyCommand extends PolyCommand {
        // Edge case 1: Already public - should skip
        public function getOptions() { return []; }
    }

    class AnotherCommand extends PolyCommand {
        // Edge case 2: No getOptions method - should skip
    }

    class ThirdCommand extends PolyCommand {
        // Edge case 3: Static method - should still work
        protected static function getOptions() { return []; }
    }
}
-----
<?php

namespace SilverStripe\PolyExecution {
    class PolyCommand {
        public function getOptions() {
            return [];
        }
    }
}

namespace App\Commands {
    use SilverStripe\PolyExecution\PolyCommand;

    class MyCommand extends PolyCommand {
        // Edge case 1: Already public - should skip
        public function getOptions() { return []; }
    }

    class AnotherCommand extends PolyCommand {
        // Edge case 2: No getOptions method - should skip
    }

    class ThirdCommand extends PolyCommand {
        // Edge case 3: Static method - should still work
        public static function getOptions() { return []; }
    }
}