<?php

namespace Test\Michel\Console\Command;

use Michel\Console\Command\CommandInterface;
use Michel\Console\InputInterface;
use Michel\Console\Option\CommandOption;
use Michel\Console\OutputInterface;

class UserListCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'app:user:list';
    }

    public function getDescription(): string
    {
        return 'TEST : User list';
    }

    public function getOptions(): array
    {
        return [
            CommandOption::withValue('limit', 'l', 'Limit the number of results', 100),
        ];
    }

    public function getArguments(): array
    {
        return [
        ];
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->write('Test OK : User list');
        $output->write(sprintf('LIMIT : %d', $input->getOptionValue('limit')));
    }
}
