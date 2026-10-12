<?php

namespace SilverStripe\Control {
    class Controller {
        public function __construct() {}
        public function __constructWithArgs($arg1, $arg2) {}
    }
}

namespace App {
    use SilverStripe\Control\Controller;

    class MyController extends Controller
    {
        // Edge case 1: No parameters - should still work
        public function __construct()
        {
            $this->init = true;
        }
    }

    class AnotherController extends Controller
    {
        // Edge case 2: No parameters at all in parent, but current has args
        public function __construct($data)
        {
            $this->data = $data;
        }
    }

class ThirdController extends Controller
    {
        // Edge case 3: More args than parent accepts - rector adds parent::__construct() with no args
        public function __construct($arg1, $arg2, $arg3 = null)
        {
            $this->arg1 = $arg1;
            $this->arg2 = $arg2;
            $this->arg3 = $arg3;
        }
    }
}
-----
<?php

namespace SilverStripe\Control {
    class Controller {
        public function __construct() {}
    }
}

namespace App {
    use SilverStripe\Control\Controller;

    class MyController extends Controller
    {
        // Edge case 1: No parameters
        public function __construct()
        {
            parent::__construct();
            $this->init = true;
        }
    }

    class AnotherController extends Controller
    {
        // Edge case 2: Has args, parent takes none
        public function __construct($data)
        {
            parent::__construct();
            $this->data = $data;
        }
    }

    class ThirdController extends Controller
    {
        // Edge case 3: More args than parent accepts - rector adds parent call (args get lost)
        public function __construct($arg1, $arg2, $arg3 = null)
        {
            parent::__construct();
            $this->arg1 = $arg1;
            $this->arg2 = $arg2;
            $this->arg3 = $arg3;
        }
    }

    class FourthController extends Controller
    {
        // Edge case 4: Already has parent call - should NOT duplicate
        public function __construct($value)
        {
            parent::__construct();
            $this->value = $value;
        }
    }
}
    }

    class FourthController extends Controller
    {
        // Edge case 4: Already has parent call - should NOT duplicate
        public function __construct($value)
        {
            parent::__construct();
            $this->value = $value;
        }
    }
}
-----
<?php

namespace SilverStripe\Control {
    class Controller {
        public function __construct() {}
        public function __constructWithArgs($arg1, $arg2) {}
    }
}

namespace App {
    use SilverStripe\Control\Controller;

    class MyController extends Controller
    {
        // Edge case 1: No parameters - should still work
        public function __construct()
        {
            parent::__construct();
            $this->init = true;
        }
    }

    class AnotherController extends Controller
    {
        // Edge case 2: No parameters at all in parent, but current has args
        public function __construct($data)
        {
            parent::__construct(); // Should add with no args
            $this->data = $data;
        }
    }

    class ThirdController extends Controller
    {
        // Edge case 3: More args than parent accepts
        public function __construct($arg1, $arg2, $arg3 = null)
        {
            parent::__construct($arg1, $arg2);
            $this->arg1 = $arg1;
            $this->arg2 = $arg2;
            $this->arg3 = $arg3;
        }
    }

    class FourthController extends Controller
    {
        // Edge case 4: Already has parent call - should NOT duplicate
        public function __construct($value)
        {
            parent::__construct();
            $this->value = $value;
        }
    }
}