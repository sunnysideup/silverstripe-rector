<?php

namespace Symfony\Component\Console\Input {
    class InputOption {
        public const VALUE_NONE = 1;
        public const VALUE_OPTIONAL = 2;
        public function __construct(string $name, $shortcut = null, int $mode = null, string $description = '', $default = null) {}
    }
}

namespace App\Command {
    use Symfony\Component\Console\Input\InputOption;

    class BaseCommand {
        public function getOptions(): array { return []; }
    }

    class MyCommand extends BaseCommand
    {
        public function getOptions(): array
        {
            return array_merge(
                parent::getOptions(),
                [
                    ['limit', 'l', InputOption::VALUE_OPTIONAL, 'Limit number of examples'],
                    ['nofrontend', 'f', InputOption::VALUE_NONE, 'Exclude frontend links'],
                    ['nobackend', 'b', InputOption::VALUE_NONE, 'Exclude backend links']
                ]
            );
        }
    }
}
-----
<?php

namespace Symfony\Component\Console\Input {
    class InputOption {
        public const VALUE_NONE = 1;
        public const VALUE_OPTIONAL = 2;
        public function __construct(string $name, $shortcut = null, int $mode = null, string $description = '', $default = null) {}
    }
}

namespace App\Command {
    use Symfony\Component\Console\Input\InputOption;

    class BaseCommand {
        public function getOptions(): array { return []; }
    }

    class MyCommand extends BaseCommand
    {
        public function getOptions(): array
        {
            return array_merge(
                parent::getOptions(),
                [
                    new \Symfony\Component\Console\Input\InputOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'Limit number of examples'),
                    new \Symfony\Component\Console\Input\InputOption('nofrontend', 'f', InputOption::VALUE_NONE, 'Exclude frontend links'),
                    new \Symfony\Component\Console\Input\InputOption('nobackend', 'b', InputOption::VALUE_NONE, 'Exclude backend links')
                ]
            );
        }
    }
}
