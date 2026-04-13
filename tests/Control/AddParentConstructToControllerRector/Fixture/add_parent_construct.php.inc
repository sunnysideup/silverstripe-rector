<?php

namespace SilverStripe\Control {
    class Controller {
        public function __construct() {}
    }
}

namespace App {
    use SilverStripe\Control\Controller;

    class PropertyHolder {}

    class PropertyHolderController extends Controller
    {
        public $locatable;
        public $parent;

        public function __construct(?PropertyHolder $model = null)
        {
            $this->locatable = $this;
            $this->parent = $this;
        }
    }

    class AnotherController extends Controller
    {
        // This should not be touched since it already calls parent
        public function __construct()
        {
            parent::__construct();
            $this->init();
        }
    }

    class UnrelatedClass
    {
        // This should not be touched because it does not extend Controller
        public function __construct($data)
        {
            $this->data = $data;
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

    class PropertyHolder {}

    class PropertyHolderController extends Controller
    {
        public $locatable;
        public $parent;

        public function __construct(?PropertyHolder $model = null)
        {
            parent::__construct($model);
            $this->locatable = $this;
            $this->parent = $this;
        }
    }

    class AnotherController extends Controller
    {
        // This should not be touched since it already calls parent
        public function __construct()
        {
            parent::__construct();
            $this->init();
        }
    }

    class UnrelatedClass
    {
        // This should not be touched because it does not extend Controller
        public function __construct($data)
        {
            $this->data = $data;
        }
    }
}
