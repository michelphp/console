<?php

namespace Michel\Console;

use RuntimeException;
use const PHP_EOL;

final class Output implements OutputInterface
{
    /**
     * @var callable
     */
    private $output;

    private bool $writesToStandardStreams = false;

    public function __construct(?callable $output = null)
    {
        if ($output === null) {
            $this->writesToStandardStreams = true;
            $output = function ($message, $error = false) {
                if ($error) {
                    fwrite(STDERR, $message);
                    return;
                }
                fwrite(STDOUT, $message);
            };
        }
        $this->output = $output;
    }

    /**
     * @var bool
     */
    private bool $verbose = false;

    public function error(string $message): void
    {
        $output = $this->output;
        $output($message, true);
    }

    public function write(string $message): void
    {
        $output = $this->output;
        $output($message);
    }

    public function writeln(string $message): void
    {
        $this->write($message);
        $this->write(PHP_EOL);
    }

    public function setVerbose(bool $verbose): void
    {
        $this->verbose = $verbose;
    }

    public function isVerbose(): bool
    {
        return $this->verbose;
    }

    /**
     * Whether colors may be written.
     * False when STDOUT (or STDERR for errors) is redirected to a file or a pipe, or when NO_COLOR is set.
     * Always true with a custom output callable.
     */
    public function isDecorated(bool $error = false): bool
    {
        if (!$this->writesToStandardStreams) {
            return true;
        }
        if (getenv('NO_COLOR') !== false && getenv('NO_COLOR') !== '') {
            return false;
        }
        $stream = $error ? 'STDERR' : 'STDOUT';
        if (!defined($stream) || !function_exists('stream_isatty')) {
            return true;
        }
        return stream_isatty(constant($stream));
    }
}
