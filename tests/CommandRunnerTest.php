<?php

namespace Test\Michel\Console;

use Michel\Console\CommandParser;
use Michel\Console\CommandRunner;
use Michel\Console\Output;
use Michel\UniTester\TestCase;
use Test\Michel\Console\Command\CacheClearCommand;
use Test\Michel\Console\Command\MakeControllerCommand;
use Test\Michel\Console\Command\MakeEntityCommand;
use Test\Michel\Console\Command\UserCreateCommand;
use Test\Michel\Console\Command\UserDisabledCommand;
use Test\Michel\Console\Command\UserListCommand;
use Test\Michel\Console\Command\UserResetPasswordCommand;

class CommandRunnerTest extends TestCase
{
    private CommandRunner $commandRunner;

    protected function setUp(): void
    {
        $this->commandRunner = new CommandRunner([
            new CacheClearCommand(),
            new MakeControllerCommand(),
            new MakeEntityCommand(),
            new UserCreateCommand(),
            new UserDisabledCommand(),
            new UserResetPasswordCommand(),
            new UserListCommand(),
        ]);
    }

    protected function tearDown(): void
    {
        // TODO: Implement tearDown() method.
    }

    protected function execute(): void
    {
        $exitCode = $this->commandRunner->run(new CommandParser(self::createArgv(['c:c'])), new Output(function ($message) {
            $this->assertEquals('Test OK : Clear cache', $message);
            })
        );
        $this->assertEquals(0, $exitCode);

        $message = '';
        $exitCode = $this->commandRunner->run(new CommandParser(self::createArgv(['c:c', '--verbose'])), new Output(function ($outputMessage) use (&$message) {
                $message .= $outputMessage;
            })
        );
        $this->assertStringContains( $message, 'Test OK : Clear cache');
        $this->assertStringContains( $message, 'Execution time:');
        $this->assertStringContains( $message, 'Peak memory usage:');
        $this->assertEquals(0, $exitCode);


        $messages = [];
        $exitCode = $this->commandRunner->run(new CommandParser(self::createArgv(['app:user:list'])), new Output(function ($message) use(&$messages){
                $messages[] = $message;
            })
        );
        $this->assertEquals(0, $exitCode);
        $this->assertEquals('Test OK : User list', $messages[0]);
        $this->assertEquals('LIMIT : 100', $messages[1]);

        $messages = [];
        $exitCode = $this->commandRunner->run(new CommandParser(self::createArgv(['app:user:list', '-l', '1000'])), new Output(function ($message) use(&$messages){
                $messages[] = $message;
            })
        );
        $this->assertEquals(0, $exitCode);
        $this->assertEquals('Test OK : User list', $messages[0]);
        $this->assertEquals('LIMIT : 1000', $messages[1]);
    }

    private static function createArgv(array $argv): array
    {
        return array_merge(['bin/console'], $argv);
    }
}
