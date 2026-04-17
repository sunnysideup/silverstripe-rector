<?php


// Dummy class to satisfy PHPStan's inheritance check during the test
namespace SilverStripe\Dev {
    class BuildTask {}
}

namespace App\Tasks {
    use SilverStripe\Dev\BuildTask;

    class MyCustomTask extends BuildTask
    {
        // Testing missing type and incorrect visibility
        private $title = 'My Task Title';
        
        // Testing an existing property that shouldn't be touched
        public $description = 'Task description';
    }
    
    class AnotherTask extends BuildTask
    {
        // Testing missing type but correct visibility
        protected $title = 'Another Task';
    }
    class AnotherTask2 extends BuildTask
    {
        // Testing missing type but correct visibility
        public string $title = 'Another Task';
    }
}
-----
<?php


// Dummy class to satisfy PHPStan's inheritance check during the test
namespace SilverStripe\Dev {
    class BuildTask {}
}

namespace App\Tasks {
    use SilverStripe\Dev\BuildTask;

    class MyCustomTask extends BuildTask
    {
        // Testing missing type and incorrect visibility
        protected string $title = 'My Task Title';
        
        // Testing an existing property that shouldn't be touched
        public $description = 'Task description';
    }
    
    class AnotherTask extends BuildTask
    {
        // Testing missing type but correct visibility
        protected string $title = 'Another Task';
    }
    class AnotherTask2 extends BuildTask
    {
        // Testing missing type but correct visibility
        protected string $title = 'Another Task';
    }
}