<?php

namespace SilverStripe\Dev {
    if (!class_exists(BuildTask::class)) {
        class BuildTask {}
    }
}

namespace App\Tasks {
    use SilverStripe\Dev\BuildTask;

    // 1. Private non-static
    class Task1 extends BuildTask {
        private $description = 'Desc 1';
    }

    // 2. Public non-static
    class Task2 extends BuildTask {
        public $description = 'Desc 2';
    }

    // 3. Protected non-static
    class Task3 extends BuildTask {
        protected $description = 'Desc 3';
    }

    // 4. Private static untyped
    class Task4 extends BuildTask {
        private static $description = 'Desc 4';
    }

    // 5. Public static with wrong type
    class Task5 extends BuildTask {
        public static int $description = 101;
    }

    // 6. Correct usage (idempotency)
    class Task6 extends BuildTask {
        protected static string $description = 'Correct';
    }

    // 7. Recursive inheritance
    class SubTask extends Task1 {
        private $description = 'Sub';
    }

    // 8. Docblock preservation
    class Task8 extends BuildTask {
        /** @var string */
        private $description = 'With Doc';
    }

    // 9. Readonly (should be stripped)
    class Task9 extends BuildTask {
        private readonly string $description;
    }

    // 10. Typed but non-static
    class Task10 extends BuildTask {
        protected string $description = 'Typed non-static';
    }

    // 11. Multi-flag static public
    class Task11 extends BuildTask {
        static public $description = 'Reverse order flags';
    }

    // 12. Unrelated class
    class Ignored {
        private $description = 'Ignore';
    }
}
-----
<?php

namespace SilverStripe\Dev {
    if (!class_exists(BuildTask::class)) {
        class BuildTask {}
    }
}

namespace App\Tasks {
    use SilverStripe\Dev\BuildTask;

    // 1. Private non-static
    class Task1 extends BuildTask {
        protected static string $description = 'Desc 1';
    }

    // 2. Public non-static
    class Task2 extends BuildTask {
        protected static string $description = 'Desc 2';
    }

    // 3. Protected non-static
    class Task3 extends BuildTask {
        protected static string $description = 'Desc 3';
    }

    // 4. Private static untyped
    class Task4 extends BuildTask {
        protected static string $description = 'Desc 4';
    }

    // 5. Public static with wrong type
    class Task5 extends BuildTask {
        protected static string $description = 101;
    }

    // 6. Correct usage (idempotency)
    class Task6 extends BuildTask {
        protected static string $description = 'Correct';
    }

    // 7. Recursive inheritance
    class SubTask extends Task1 {
        protected static string $description = 'Sub';
    }

    // 8. Docblock preservation
    class Task8 extends BuildTask {
        /** @var string */
        protected static string $description = 'With Doc';
    }

    // 9. Readonly (should be stripped)
    class Task9 extends BuildTask {
        protected static string $description;
    }

    // 10. Typed but non-static
    class Task10 extends BuildTask {
        protected static string $description = 'Typed non-static';
    }

    // 11. Multi-flag static public
    class Task11 extends BuildTask {
        protected static string $description = 'Reverse order flags';
    }

    // 12. Unrelated class
    class Ignored {
        private $description = 'Ignore';
    }
}
