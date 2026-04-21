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
            return 0;
        }
    }

    class LogicTask extends BuildTask {
        public function run($request) {
            if (true) {
                return 1;
            }
            return 0;
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
            return 0;
        }
    }

    class LogicTask extends BuildTask {
        protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \SilverStripe\Console\PolyOutput $output): int {
            if (true) {
                return 1;
            }
            return 0;
        }
    }

    class NonTask {
        public function run($request) {
            // Should not change
        }
    }
}
