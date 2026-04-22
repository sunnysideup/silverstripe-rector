<?php

namespace App\Tasks;

use SilverStripe\Dev\BuildTask;
use Symfony\Component\Console\Command\Command;

class NoReturnTask extends BuildTask {
    protected function execute($input, $output): int {
        $a = 1;
        $b = 2;
    }
}

class WrongReturnTask extends BuildTask {
    protected function execute($input, $output): int {
        $a = 1;
        return true;
    }
}

class ReturnVoidTask extends BuildTask {
    protected function execute($input, $output): int {
        echo "done";
        return;
    }
}

class EmptyTask extends BuildTask {
    protected function execute($input, $output): int {
    }
}

class AlreadyCorrectTask extends BuildTask {
    protected function execute($input, $output): int {
        $a = 1;
        return Command::SUCCESS;
    }
}

class FullyQualifiedCorrectTask extends BuildTask {
    protected function execute($input, $output): int {
        $a = 1;
        return \Symfony\Component\Console\Command\Command::SUCCESS;
    }
}

class EarlyReturnIgnoredTask extends BuildTask {
    protected function execute($input, $output): int {
        if (false) {
            return 1;
        }
        $a = 1;
    }
}
-----
<?php

namespace App\Tasks;

use SilverStripe\Dev\BuildTask;
use Symfony\Component\Console\Command\Command;

class NoReturnTask extends BuildTask {
    protected function execute($input, $output): int {
        $a = 1;
        $b = 2;
        return \Symfony\Component\Console\Command\Command::SUCCESS;
    }
}

class WrongReturnTask extends BuildTask {
    protected function execute($input, $output): int {
        $a = 1;
        return \Symfony\Component\Console\Command\Command::SUCCESS;
    }
}

class ReturnVoidTask extends BuildTask {
    protected function execute($input, $output): int {
        echo "done";
        return \Symfony\Component\Console\Command\Command::SUCCESS;
    }
}

class EmptyTask extends BuildTask {
    protected function execute($input, $output): int {
        return \Symfony\Component\Console\Command\Command::SUCCESS;
    }
}

class AlreadyCorrectTask extends BuildTask {
    protected function execute($input, $output): int {
        $a = 1;
        return Command::SUCCESS;
    }
}

class FullyQualifiedCorrectTask extends BuildTask {
    protected function execute($input, $output): int {
        $a = 1;
        return \Symfony\Component\Console\Command\Command::SUCCESS;
    }
}

class EarlyReturnIgnoredTask extends BuildTask {
    protected function execute($input, $output): int {
        if (false) {
            return 1;
        }
        $a = 1;
        return \Symfony\Component\Console\Command\Command::SUCCESS;
    }
}
