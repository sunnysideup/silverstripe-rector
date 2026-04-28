<?php

namespace SilverStripe\Control {
    class Controller {
        public function render($params = null) { return null; }
    }
}

namespace App\Controllers {
    use SilverStripe\Control\Controller;

    // 1. Missing params, missing return type
    class C1 extends Controller {
        public function render() { return null; }
    }

    // 2. Wrong parameter name
    class C2 extends Controller {
        public function render($context = null) { return null; }
    }

    // 3. Missing default value for parameter
    class C3 extends Controller {
        public function render($params) { return null; }
    }

    // 4. Too many parameters
    class C4 extends Controller {
        public function render($params = null, $extra = true) { return null; }
    }

    // 5. Existing return type is incorrect
    class C5 extends Controller {
        public function render($params = null): string { return ''; }
    }

    // 6. Idempotency test (Already perfectly formatted)
    class C6 extends Controller {
        public function render($params = null): \SilverStripe\ORM\FieldType\DBHTMLText { return null; }
    }

    // 7. Parameter has a type hint that needs to be stripped
    class C7 extends Controller {
        public function render(array $params = null) { return null; }
    }

    // 8. Default value is uppercase NULL
    class C8 extends Controller {
        public function render($params = NULL) { return null; }
    }

    // 9. Method is abstract in an abstract subclass
    abstract class C9 extends Controller {
        abstract public function render();
    }

    // 10. Method uses different casing for 'render'
    class C10 extends Controller {
        public function RENDER() { return null; }
    }
}

namespace App\Other {
    // 11. Class DOES NOT extend Controller (Should be completely ignored)
    class C11 {
        public function render($foo) { return null; }
    }

    // 12. Deeply nested Controller inheritance
    class BaseC extends \SilverStripe\Control\Controller {
    }
    class C12 extends BaseC {
        public function render($x) { return null; }
    }
}
-----
<?php

namespace SilverStripe\Control {
    class Controller {
        public function render($params = null) { return null; }
    }
}

namespace App\Controllers {
    use SilverStripe\Control\Controller;

    // 1. Missing params, missing return type
    class C1 extends Controller {
        public function render($params = null): \SilverStripe\ORM\FieldType\DBHTMLText { return null; }
    }

    // 2. Wrong parameter name
    class C2 extends Controller {
        public function render($params = null): \SilverStripe\ORM\FieldType\DBHTMLText { return null; }
    }

    // 3. Missing default value for parameter
    class C3 extends Controller {
        public function render($params = null): \SilverStripe\ORM\FieldType\DBHTMLText { return null; }
    }

    // 4. Too many parameters
    class C4 extends Controller {
        public function render($params = null): \SilverStripe\ORM\FieldType\DBHTMLText { return null; }
    }

    // 5. Existing return type is incorrect
    class C5 extends Controller {
        public function render($params = null): \SilverStripe\ORM\FieldType\DBHTMLText { return ''; }
    }

    // 6. Idempotency test (Already perfectly formatted)
    class C6 extends Controller {
        public function render($params = null): \SilverStripe\ORM\FieldType\DBHTMLText { return null; }
    }

    // 7. Parameter has a type hint that needs to be stripped
    class C7 extends Controller {
        public function render($params = null): \SilverStripe\ORM\FieldType\DBHTMLText { return null; }
    }

    // 8. Default value is uppercase NULL
    class C8 extends Controller {
        public function render($params = null): \SilverStripe\ORM\FieldType\DBHTMLText { return null; }
    }

    // 9. Method is abstract in an abstract subclass
    abstract class C9 extends Controller {
        abstract public function render($params = null): \SilverStripe\ORM\FieldType\DBHTMLText;
    }

    // 10. Method uses different casing for 'render'
    class C10 extends Controller {
        public function RENDER($params = null): \SilverStripe\ORM\FieldType\DBHTMLText { return null; }
    }
}

namespace App\Other {
    // 11. Class DOES NOT extend Controller (Should be completely ignored)
    class C11 {
        public function render($foo) { return null; }
    }

    // 12. Deeply nested Controller inheritance
    class BaseC extends \SilverStripe\Control\Controller {
    }
    class C12 extends BaseC {
        public function render($params = null): \SilverStripe\ORM\FieldType\DBHTMLText { return null; }
    }
}
