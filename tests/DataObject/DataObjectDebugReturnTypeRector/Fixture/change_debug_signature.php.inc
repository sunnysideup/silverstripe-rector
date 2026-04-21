<?php

namespace App\Models {
    use SilverStripe\ORM\DataObject;

    class StandardObject extends DataObject {
        public function debug() {
            return "Standard Debug";
        }
    }

    class ProtectedObject extends DataObject {
        protected function debug() {
            return "Protected Debug";
        }
    }

    class PrivateObject extends DataObject {
        private function debug() {
            return "Private Debug";
        }
    }

    class MissingReturnObject extends DataObject {
        public function debug() {
            echo "Missing Return Debug";
        }
    }

    class AlreadyCorrectObject extends DataObject {
        public function debug(): string {
            return "Already Correct";
        }
    }

    class WithParamsObject extends DataObject {
        public function debug($verbose = false) {
            return "With Params Debug";
        }
    }

    class NonDataObject {
        protected function debug() {
            return "Not a DataObject";
        }
    }
}
-----
<?php

namespace App\Models {
    use SilverStripe\ORM\DataObject;

    class StandardObject extends DataObject {
        public function debug(): string
        {
            return "Standard Debug";
        }
    }

    class ProtectedObject extends DataObject {
        public function debug(): string
        {
            return "Protected Debug";
        }
    }

    class PrivateObject extends DataObject {
        public function debug(): string
        {
            return "Private Debug";
        }
    }

    class MissingReturnObject extends DataObject {
        public function debug(): string
        {
            echo "Missing Return Debug";
            return '';
        }
    }

    class AlreadyCorrectObject extends DataObject {
        public function debug(): string {
            return "Already Correct";
        }
    }

    class WithParamsObject extends DataObject {
        public function debug($verbose = false): string
        {
            return "With Params Debug";
        }
    }

    class NonDataObject {
        protected function debug() {
            return "Not a DataObject";
        }
    }
}
