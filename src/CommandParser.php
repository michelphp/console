<?php

namespace Michel\Console;

use InvalidArgumentException;
use function strlen;
use function strncmp;

final class CommandParser
{
    /**
     * @var string|null
     */
    private ?string $cmdName;
    private array $options = [];
    private array $arguments = [];
    private array $argv;

    /**
     * @param array|null $argv
     * @param string[] $flags Shortcuts that never take a value: "-f value" keeps "value" as an argument
     */
    public function __construct(?array $argv = null, array $flags = [])
    {
        $argv = $argv ?? $_SERVER['argv'] ?? [];
        $this->argv = $argv;
        array_shift($argv);
        $this->cmdName = $argv[0] ?? null;
        $flags = array_merge($flags, ['h', 'v']);

        $ignoreKeys = [0];
        foreach ($argv as $key => $value) {
            if (in_array($key, $ignoreKeys, true)) {
                continue;
            }

            if (self::startsWith($value, '--')) {
                $it = explode("=", ltrim($value, '-'), 2);
                $optionName = $it[0];
                $optionValue = $it[1] ?? true;
                $this->options[$optionName] = $optionValue;
            } elseif (self::startsWith($value, '-')) {
                $optionName = ltrim($value, '-');
                if (strlen($optionName) > 1) {
                    $options = str_split($optionName);
                    foreach ($options as $option) {
                        $this->options[$option] = true;
                    }
                } else {
                    $this->options[$optionName] = true;
                    if (!in_array($optionName, $flags, true) && isset($argv[$key + 1]) && !self::startsWith($argv[$key + 1], '-')) {
                        $ignoreKeys[] = $key + 1;
                        $this->options[$optionName] = $argv[$key + 1];
                    }
                }
            } else {
                $this->arguments[] = $value;
            }
        }
    }

    /**
     * Parses the same argv again, knowing which shortcuts are flags.
     *
     * @param string[] $flags
     */
    public function withFlags(array $flags): self
    {
        return new self($this->argv, $flags);
    }

    public function getCommandName(): ?string
    {
        return $this->cmdName;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function hasOption(string $name): bool
    {
        return array_key_exists($name, $this->options);
    }

    public function getOptionValue(string $name)
    {
        if (!$this->hasOption($name)) {
            throw new InvalidArgumentException(sprintf('Option "%s" is not defined.', $name));
        }
        return $this->options[$name];
    }

    public function getArgumentValue(string $name)
    {
        if (!$this->hasArgument($name)) {
            throw new InvalidArgumentException(sprintf('Argument "%s" is not defined.', $name));
        }
        return $this->arguments[$name];
    }

    public function hasArgument(string $name): bool
    {
        return array_key_exists($name, $this->arguments);
    }

    private static function startsWith(string $haystack, string $needle): bool
    {
        return strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}
