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

    class ReturnOneTask extends BuildTask {
        public function run($request) {
            return 1;
        }
    }

    class LogicTask extends BuildTask {
        public function run($request) {
            if (isset($request)) {
                return 2;
            }
            return 0;
        }
    }

    class EmptyTask extends BuildTask {
        public function run($request) {}
    }

    class NonTask {
        public function run($request) {
            return 'not changed';
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

    class ReturnOneTask extends BuildTask {
        protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \SilverStripe\Console\PolyOutput $output): int {
            return 1;
        }
    }

    class LogicTask extends BuildTask {
        protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \SilverStripe\Console\PolyOutput $output): int {
            if (isset($request)) {
                return 2;
            }
            return 0;
        }
    }

    class EmptyTask extends BuildTask {
        protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \SilverStripe\Console\PolyOutput $output): int {
            return 0;
        }
    }

    class NonTask {
        public function run($request) {
            return 'not changed';
        }
    }
}
