<?php

namespace App\Tasks {
    // Case 1: Simple private untyped
    class Task1 extends \SilverStripe\Dev\BuildTask {
        private $description = 'Task 1';
    }

    // Case 2: Public untyped
    class Task2 extends \SilverStripe\Dev\BuildTask {
        public $description = 'Task 2';
    }

    // Case 3: Protected untyped but not static
    class Task3 extends \SilverStripe\Dev\BuildTask {
        protected $description = 'Task 3';
    }

    // Case 4: Static but private and no type
    class Task4 extends \SilverStripe\Dev\BuildTask {
        private static $description = 'Task 4';
    }

    // Case 5: Static and public with wrong type
    class Task5 extends \SilverStripe\Dev\BuildTask {
        public static int $description = 123;
    }

    // Case 6: Already correct (should remain unchanged)
    class Task6 extends \SilverStripe\Dev\BuildTask {
        protected static string $description = 'Task 6';
    }

    // Case 7: Inherited class check
    class SubTask extends Task1 {
        private $description = 'SubTask';
    }

    // Case 8: Property with DocBlock preservation
    class Task8 extends \SilverStripe\Dev\BuildTask {
        /** @var string */
        private $description = 'Task 8';
    }

    // Case 9: Readonly property (should be converted to static/protected/string)
    class Task9 extends \SilverStripe\Dev\BuildTask {
        private readonly string $description;
    }

    // Case 10: Unrelated class (should not be touched)
    class NotATask {
        private $description = 'I stay private';
    }
}
-----
<?php

namespace App\Tasks {
    // Case 1: Simple private untyped
    class Task1 extends \SilverStripe\Dev\BuildTask {
        protected static string $description = 'Task 1';
    }

    // Case 2: Public untyped
    class Task2 extends \SilverStripe\Dev\BuildTask {
        protected static string $description = 'Task 2';
    }

    // Case 3: Protected untyped but not static
    class Task3 extends \SilverStripe\Dev\BuildTask {
        protected static string $description = 'Task 3';
    }

    // Case 4: Static but private and no type
    class Task4 extends \SilverStripe\Dev\BuildTask {
        protected static string $description = 'Task 4';
    }

    // Case 5: Static and public with wrong type
    class Task5 extends \SilverStripe\Dev\BuildTask {
        protected static string $description = 123;
    }

    // Case 6: Already correct (should remain unchanged)
    class Task6 extends \SilverStripe\Dev\BuildTask {
        protected static string $description = 'Task 6';
    }

    // Case 7: Inherited class check
    class SubTask extends Task1 {
        protected static string $description = 'SubTask';
    }

    // Case 8: Property with DocBlock preservation
    class Task8 extends \SilverStripe\Dev\BuildTask {
        /** @var string */
        protected static string $description = 'Task 8';
    }

    // Case 9: Readonly property (should be converted to static/protected/string)
    class Task9 extends \SilverStripe\Dev\BuildTask {
        protected static string $description;
    }

    // Case 10: Unrelated class (should not be touched)
    class NotATask {
        private $description = 'I stay private';
    }
}
