<?php

namespace Test\Michel\Console\Command;

use Michel\Console\Argument\CommandArgument;
use Michel\Console\Command\CommandInterface;
use Michel\Console\InputInterface;
use Michel\Console\Option\CommandOption;
use Michel\Console\OutputInterface;

class FooCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'foo';
    }

    public function getDescription(): string
    {
        return 'Performs the foo operation with optional parameters.';
    }

    public function getOptions(): array
    {
        return [
            new CommandOption('verbose', 'v', 'Enable verbose output', true),
            new CommandOption('output', 'o', 'Specify output file', false)
        ];
    }

    public function getArguments(): array
    {
        return [
            new CommandArgument('input', false, 'none', 'The input file for the foo operation')
        ];
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('Test OK');
        $output->writeln('ARGUMENTS: ' . json_encode($input->getArguments()));
        $output->writeln('OPTIONS: ' . json_encode($input->getOptions()));
    }
}
