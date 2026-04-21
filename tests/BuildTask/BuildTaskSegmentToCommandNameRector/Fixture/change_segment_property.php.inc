<?php

namespace SilverStripe\Dev {
    class BuildTask {}
}

namespace App\Tasks {
    class StandardTask extends \SilverStripe\Dev\BuildTask {
        private static $segment = 'standard-task';
    }

    class ProtectedTask extends \SilverStripe\Dev\BuildTask {
        protected static $segment = 'protected-task';
    }

    class PublicTask extends \SilverStripe\Dev\BuildTask {
        public static $segment = 'public-task';
    }

    class AlreadyTypedTask extends \SilverStripe\Dev\BuildTask {
        private static string $segment = 'typed-task';
    }

    class NoDefaultValueTask extends \SilverStripe\Dev\BuildTask {
        private static $segment;
    }

    class NonStaticTask extends \SilverStripe\Dev\BuildTask {
        private $segment = 'non-static-task';
    }

    class NonTask {
        private static $segment = 'not-a-task';
    }
}
-----
<?php

namespace SilverStripe\Dev {
    class BuildTask {}
}

namespace App\Tasks {
    class StandardTask extends \SilverStripe\Dev\BuildTask {
        protected static string $commandName = 'standard-task';
    }

    class ProtectedTask extends \SilverStripe\Dev\BuildTask {
        protected static string $commandName = 'protected-task';
    }

    class PublicTask extends \SilverStripe\Dev\BuildTask {
        protected static string $commandName = 'public-task';
    }

    class AlreadyTypedTask extends \SilverStripe\Dev\BuildTask {
        protected static string $commandName = 'typed-task';
    }

    class NoDefaultValueTask extends \SilverStripe\Dev\BuildTask {
        protected static string $commandName;
    }

    class NonStaticTask extends \SilverStripe\Dev\BuildTask {
        protected static string $commandName = 'non-static-task';
    }

    class NonTask {
        private static $segment = 'not-a-task';
    }
}
