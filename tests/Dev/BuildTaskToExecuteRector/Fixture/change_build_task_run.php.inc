<?php

namespace App\Tasks {
    use SilverStripe\Dev\BuildTask;
    class StandardTask extends BuildTask {
        public function run($request) {
            echo "Standard";
        }
    }

    class NoParamsTask extends BuildTask {
        public function run() {
            echo "No Params";
        }
    }

    class TypeHintedTask extends BuildTask {
        public function run(\SilverStripe\Control\HTTPRequest $request) {
            echo "Type Hinted";
        }
    }

    class ExistingReturnTask extends BuildTask {
        public function run($request) {
            return 1;
        }
    }

    class LogicTask extends BuildTask {
        public function run($request) {
            if (mt_rand(0, 1)) {
                return 2;
            }
            return 0;
        }
    }

    class SubclassedTask extends StandardTask {
        public function run($request) {
            parent::run($request);
        }
    }

    class NonTask {
        public function run($request) {
            // Should not change
        }
    }
}
-----
<?php

namespace App\Tasks {
    use SilverStripe\Dev\BuildTask;
    class StandardTask extends BuildTask {
        protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \SilverStripe\Console\PolyOutput $output): int {
            echo "Standard";
            return 0;
        }
    }

    class NoParamsTask extends BuildTask {
        protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \SilverStripe\Console\PolyOutput $output): int {
            echo "No Params";
            return 0;
        }
    }

    class TypeHintedTask extends BuildTask {
        protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \SilverStripe\Console\PolyOutput $output): int {
            echo "Type Hinted";
            return 0;
        }
    }

    class ExistingReturnTask extends BuildTask {
        protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \SilverStripe\Console\PolyOutput $output): int {
            return 1;
        }
    }

    class LogicTask extends BuildTask {
        protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \SilverStripe\Console\PolyOutput $output): int {
            if (mt_rand(0, 1)) {
                return 2;
            }
            return 0;
        }
    }

    class SubclassedTask extends StandardTask {
        protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \SilverStripe\Console\PolyOutput $output): int {
            parent::run($request);
            return 0;
        }
    }

    class NonTask {
        public function run($request) {
            // Should not change
        }
    }
}
