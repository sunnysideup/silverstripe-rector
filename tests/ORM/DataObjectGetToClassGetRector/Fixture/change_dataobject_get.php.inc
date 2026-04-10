<?php

namespace SilverStripe\ORM {
    class DataObject {}
}

namespace App\Models {
    class OrderStep extends \SilverStripe\ORM\DataObject {}
}

namespace App\Controllers {
    use SilverStripe\ORM\DataObject;
    use App\Models\OrderStep;

    class MyController
    {
        public function index()
        {
            // Testing ::class constant
            $steps = DataObject::get(OrderStep::class);
            
            // Testing string literal FQCN
            $moreSteps = DataObject::get('App\Models\OrderStep');
            
            // Testing string literal standard (Silverstripe 3 legacy style)
            $legacySteps = DataObject::get('OrderStep');
            
            // Should not be touched (already correct)
            $alreadyCorrect = OrderStep::get();
        }
    }
}
-----
<?php

namespace SilverStripe\ORM {
    class DataObject {}
}

namespace App\Models {
    class OrderStep extends \SilverStripe\ORM\DataObject {}
}

namespace App\Controllers {
    use SilverStripe\ORM\DataObject;
    use App\Models\OrderStep;

    class MyController
    {
        public function index()
        {
            // Testing ::class constant
            $steps = OrderStep::get();
            
            // Testing string literal FQCN
            $moreSteps = \App\Models\OrderStep::get();
            
            // Testing string literal standard (Silverstripe 3 legacy style)
            $legacySteps = \OrderStep::get();
            
            // Should not be touched (already correct)
            $alreadyCorrect = OrderStep::get();
        }
    }
}
