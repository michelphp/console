<?php

namespace Test\Michel\Console;

use InvalidArgumentException;
use Michel\Console\CommandParser;
use Michel\Console\CommandRunner;
use Michel\Console\Output;
use Michel\Console\Output\ConsoleOutput;
use Michel\UniTester\TestCase;
use Test\Michel\Console\Command\GreetAllCommand;
use Test\Michel\Console\Command\GreetCommand;

class CommandRunnerEdgeCaseTest extends TestCase
{
    private CommandRunner $commandRunner;

    protected function setUp(): void
    {
        $this->commandRunner = new CommandRunner([
            new GreetCommand(),
            new GreetAllCommand(),
        ]);
    }

    protected function tearDown(): void
    {
    }

    protected function execute(): void
    {
        $this->testFlagShortcutKeepsTheNextArgument();
        $this->testExactNameIsNotAmbiguous();
        $this->testFlagWithAValueStillWorksWithoutArguments();
        $this->testHelpShortcut();
        $this->testZeroArgument();
        $this->testFlagHasNoDefaultInHelp();
        $this->testClassNameIsRejected();
        $this->testTableWithColumnsThatDoNotDivideTheWidth();
        $this->testProgressBarLimits();
    }

    public function testFlagShortcutKeepsTheNextArgument()
    {
        [$exitCode, $output] = $this->runCommand(['greet', '-s', 'john']);
        $this->assertStrictEquals(0, $exitCode);
        $this->assertStrictEquals('HELLO JOHN', $output);

        [$exitCode, $output] = $this->runCommand(['greet', '-v', 'john']);
        $this->assertStrictEquals(0, $exitCode);
        $this->assertStringContains($output, 'Hello john');
    }

    public function testExactNameIsNotAmbiguous()
    {
        [$exitCode, $output] = $this->runCommand(['greet', 'john']);
        $this->assertStrictEquals(0, $exitCode);
        $this->assertStrictEquals('Hello john', $output);

        [$exitCode] = $this->runCommand(['gre']);
        $this->assertStrictEquals(CommandRunner::CLI_AMBIGUOUS_COMMAND, $exitCode);

        $runner = new CommandRunner([new GreetAllCommand(), new GreetAllCommand()]);
        $exitCode = $runner->run(new CommandParser(['bin/console', 'greet-all']), new Output(function () {
        }));
        $this->assertStrictEquals(CommandRunner::CLI_AMBIGUOUS_COMMAND, $exitCode);
    }

    public function testFlagWithAValueStillWorksWithoutArguments()
    {
        [$exitCode, $output] = $this->runCommand(['greet-all', '-v', 'yes']);
        $this->assertStrictEquals(0, $exitCode);
        $this->assertStringContains($output, 'Hello everyone');
    }

    public function testHelpShortcut()
    {
        [$exitCode, $output] = $this->runCommand(['greet', '-h']);
        $this->assertStrictEquals(0, $exitCode);
        $this->assertStringContains($output, 'Who to greet');

        [$exitCode, $output] = $this->runCommand(['-h']);
        $this->assertStrictEquals(0, $exitCode);
        $this->assertStringContains($output, 'List of Available Commands');
    }

    public function testZeroArgument()
    {
        [$exitCode, $output] = $this->runCommand(['greet', '0']);
        $this->assertStrictEquals(0, $exitCode);
        $this->assertStrictEquals('Hello 0', $output);
    }

    public function testFlagHasNoDefaultInHelp()
    {
        [, $output] = $this->runCommand(['greet', '--help']);
        $this->assertStringContains($output, '-s, --shout');
        $this->assertFalse(strpos($output, '(default: )') !== false);
    }

    public function testClassNameIsRejected()
    {
        $this->expectException(InvalidArgumentException::class, function () {
            new CommandRunner([GreetCommand::class]);
        });
    }

    public function testTableWithColumnsThatDoNotDivideTheWidth()
    {
        $errors = [];
        set_error_handler(function (int $level, string $message) use (&$errors) {
            $errors[] = $message;
            return true;
        });
        $output = '';
        $console = new ConsoleOutput(new Output(function ($message) use (&$output) {
            $output .= $message;
        }));
        $console->table(['id', 'name', 'email'], [[1, str_repeat('a', 200), 'john@example.com']]);
        restore_error_handler();

        $this->assertEmpty($errors);
        $this->assertStringContains($output, 'john@example.com');
    }

    public function testProgressBarLimits()
    {
        $output = '';
        $console = new ConsoleOutput(new Output(function ($message) use (&$output) {
            $output .= $message;
        }));
        $console->progressBar(10, 5);
        $this->assertStrictEquals("\r[" . str_repeat('#', 25) . str_repeat(' ', 25) . '] 50%', $output);

        $output = '';
        $console->progressBar(10, 15);
        $this->assertStringContains($output, '[' . str_repeat('#', 50) . '] 150%');

        $output = '';
        $console->progressBar(0, 0);
        $this->assertStringContains($output, '[' . str_repeat('#', 50) . '] 100%');
    }

    private function runCommand(array $argv): array
    {
        $output = '';
        $exitCode = $this->commandRunner->run(
            new CommandParser(array_merge(['bin/console'], $argv)),
            new Output(function ($message) use (&$output) {
                $output .= $message;
            })
        );
        return [$exitCode, $output];
    }
}
