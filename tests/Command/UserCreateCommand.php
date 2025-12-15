<?php

namespace Test\Michel\Console\Command;

use Michel\Console\Command\CommandInterface;
use Michel\Console\InputInterface;
use Michel\Console\OutputInterface;

class UserCreateCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'app:user:create';
    }

    public function getDescription(): string
    {
        return 'TEST : User create';
    }

    public function getOptions(): array
    {
        return [
        ];
    }

    public function getArguments(): array
    {
        return [
        ];
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->write('Test OK : User create');
    }
}
