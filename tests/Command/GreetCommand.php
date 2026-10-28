<?php

namespace Test\Michel\Console\Command;

use Michel\Console\Argument\CommandArgument;
use Michel\Console\Command\CommandInterface;
use Michel\Console\InputInterface;
use Michel\Console\Option\CommandOption;
use Michel\Console\OutputInterface;

class GreetCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'greet';
    }

    public function getDescription(): string
    {
        return 'Greet someone';
    }

    public function getOptions(): array
    {
        return [
            CommandOption::flag('shout', 's', 'Greet in upper case'),
        ];
    }

    public function getArguments(): array
    {
        return [
            CommandArgument::required('name', 'Who to greet'),
        ];
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $message = 'Hello ' . $input->getArgumentValue('name');
        $output->write($input->getOptionValue('shout') ? strtoupper($message) : $message);
    }
}
