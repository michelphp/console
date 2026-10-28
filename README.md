# Michel Console

**Your CLI commands, one class each.**

Implement one interface, declare your arguments and options, register the command. Help, validation, colors, tables and prompts come with it. Nothing else to install.

PHP 7.4 to 8.4 · No dependency · Any framework

📖 **[Documentation](https://michelphp.github.io/console/docs.html)** · [Website](https://michelphp.github.io/console/)

## Install

```bash
composer require michel/console
```

## Quick start

```php
use Michel\Console\Argument\CommandArgument;
use Michel\Console\Command\CommandInterface;
use Michel\Console\InputInterface;
use Michel\Console\Option\CommandOption;
use Michel\Console\OutputInterface;

final class SendEmailCommand implements CommandInterface
{
    public function getName(): string { return 'send-email'; }

    public function getDescription(): string { return 'Sends an email to a recipient.'; }

    public function getArguments(): array
    {
        return [CommandArgument::required('recipient', 'Email address of the recipient')];
    }

    public function getOptions(): array
    {
        return [CommandOption::withValue('subject', 's', 'Subject of the email', 'No subject')];
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln(sprintf(
            'Email sent to %s: %s',
            $input->getArgumentValue('recipient'),
            $input->getOptionValue('subject')
        ));
    }
}
```

```php
#!/usr/bin/env php
<?php
// bin/console
use Michel\Console\CommandParser;
use Michel\Console\CommandRunner;
use Michel\Console\Output;

require dirname(__DIR__) . '/vendor/autoload.php';

$runner = new CommandRunner([new SendEmailCommand()]);
exit($runner->run(new CommandParser(), new Output()));
```

```bash
$ bin/console send-email john@example.com --subject="Meeting"
Email sent to john@example.com: Meeting
$ bin/console send-email --help     # description, arguments, options
$ bin/console                       # every command
```

## Why Console

| | |
|---|---|
| **No dependency** | Pure PHP with `mbstring`, `json` and `ctype`. Nothing else in your `vendor/`. |
| **Any framework** | Symfony, Laravel, Slim or none: build the `CommandRunner` yourself or take it from your container. |
| **Help for free** | `bin/console` lists your commands, `--help` describes one, `c:c` runs `cache:clear`. |
| **Output that behaves** | Messages, tables, lists, progress bars and prompts. Errors go to STDERR, colors turn off in files and pipes (and with `NO_COLOR`). |
| **Precise exit codes** | `0` success, `1` error, `10` unknown command, `11` invalid arguments, `12` ambiguous name. |

## What Console will never do

add a dependency · require a framework · scan your folders for commands · dispatch events · need a configuration file

## Documentation

The [documentation](https://michelphp.github.io/console/docs.html) covers commands, arguments, options, the command-line syntax, reading the input, `Output` and `ConsoleOutput`, colors, help and verbose mode, exit codes and testing a command.

## License

[MIT](LICENSE)
