<?php

namespace Test\Michel\Console\Command;

use Michel\Console\Command\CommandInterface;
use Michel\Console\InputInterface;
use Michel\Console\OutputInterface;

class UserResetPasswordCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'app:user:reset-password';
    }

    public function getDescription(): string
    {
        return 'TEST : User reset password';
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
        $output->write('Test OK : User reset password');
    }
}
