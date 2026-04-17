<?php

namespace SilverStripe\Dev {
    class BuildTask {}
}

namespace App\Tasks {
    use SilverStripe\Dev\BuildTask;

    class MyTask extends BuildTask
    {
        // Edge case 1: No $title property at all - should skip (no property to modify)
    }

    class TaskWithTitle extends BuildTask
    {
        // Edge case 2: Already has correct type and visibility - should remain unchanged
        protected string $title = 'My Task Title';
    }

    class TaskWithDifferentProperty extends BuildTask
    {
        // Edge case 3: Has $title but also other properties - should modify $title only
        private $title = 'Task Title';
        public $description = 'Description';
        protected $anotherField = 'value';
    }

    class TaskWithNullTitle extends BuildTask
    {
        // Edge case 4: $title with null value
        protected $title = null;
    }

    class TaskWithDifferentType extends BuildTask
    {
        // Edge case 5: Has $title with different type - should change to string
        protected $title = 'Task Title';
    }

    class TaskWithPublicVisibility extends BuildTask
    {
        // Edge case 6: Public visibility - should change to protected
        public $title = 'Public Title';
    }
}
-----
<?php

namespace SilverStripe\Dev {
    class BuildTask {}
}

namespace App\Tasks {
    use SilverStripe\Dev\BuildTask;

    class MyTask extends BuildTask
    {
        // Edge case 1: No $title property at all - should skip (no property to modify)
    }

    class TaskWithTitle extends BuildTask
    {
        // Edge case 2: Already has correct type and visibility - should remain unchanged
        protected string $title = 'My Task Title';
    }

    class TaskWithDifferentProperty extends BuildTask
    {
        // Edge case 3: Has $title but also other properties - should modify $title only
        protected string $title = 'Task Title';
        public $description = 'Description';
        protected $anotherField = 'value';
    }

    class TaskWithNullTitle extends BuildTask
    {
        // Edge case 4: $title with null value
        protected string $title = null;
    }

    class TaskWithDifferentType extends BuildTask
    {
        // Edge case 5: Has $title with different type - should change to string
        protected string $title = 'Task Title';
    }

    class TaskWithPublicVisibility extends BuildTask
    {
        // Edge case 6: Public visibility - should change to protected
        protected string $title = 'Public Title';
    }
}