<?php

namespace SilverStripe\Dev {
    if (!class_exists(BuildTask::class)) {
        class BuildTask {}
    }
}

namespace App\Tasks {
    use SilverStripe\Dev\BuildTask;

    // 1. Private non-static enabled
    class Task1 extends BuildTask {
        private $enabled = true;
    }

    // 2. Public non-static enabled
    class Task2 extends BuildTask {
        public $enabled = false;
    }

    // 3. Protected static enabled
    class Task3 extends BuildTask {
        protected static $enabled = true;
    }

    // 4. Already has a type but wrong name
    class Task4 extends BuildTask {
        private bool $enabled = false;
    }

    // 5. No default value
    class Task5 extends BuildTask {
        private $enabled;
    }

    // 6. Inherited class
    class SubTask extends Task1 {
        private $enabled = true;
    }

    // 7. Typed static public
    class Task7 extends BuildTask {
        public static bool $enabled = true;
    }

    // 8. Docblock preservation
    class Task8 extends BuildTask {
        /** @var bool */
        private $enabled = true;
    }

    // 9. Readonly conversion
    class Task9 extends BuildTask {
        private readonly bool $enabled;
    }

    // 10. Unrelated property name
    class Task10 extends BuildTask {
        private $other = true;
    }

    // 11. Unrelated class
    class NotATask {
        private $enabled = true;
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

    // 1. Private non-static enabled
    class Task1 extends BuildTask {
        private static bool $is_enabled = true;
    }

    // 2. Public non-static enabled
    class Task2 extends BuildTask {
        private static bool $is_enabled = false;
    }

    // 3. Protected static enabled
    class Task3 extends BuildTask {
        private static bool $is_enabled = true;
    }

    // 4. Already has a type but wrong name
    class Task4 extends BuildTask {
        private static bool $is_enabled = false;
    }

    // 5. No default value
    class Task5 extends BuildTask {
        private static bool $is_enabled;
    }

    // 6. Inherited class
    class SubTask extends Task1 {
        private static bool $is_enabled = true;
    }

    // 7. Typed static public
    class Task7 extends BuildTask {
        private static bool $is_enabled = true;
    }

    // 8. Docblock preservation
    class Task8 extends BuildTask {
        /** @var bool */
        private static bool $is_enabled = true;
    }

    // 9. Readonly conversion
    class Task9 extends BuildTask {
        private static bool $is_enabled;
    }

    // 10. Unrelated property name
    class Task10 extends BuildTask {
        private $other = true;
    }

    // 11. Unrelated class
    class NotATask {
        private $enabled = true;
    }
}
