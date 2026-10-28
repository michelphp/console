<?php

namespace Test\Michel\Console\Command;

use Michel\Console\Command\CommandInterface;
use Michel\Console\InputInterface;
use Michel\Console\OutputInterface;

class GreetAllCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'greet-all';
    }

    public function getDescription(): string
    {
        return 'Greet everyone';
    }

    public function getOptions(): array
    {
        return [];
    }

    public function getArguments(): array
    {
        return [];
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->write('Hello everyone');
    }
}
