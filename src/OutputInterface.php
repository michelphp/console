<?php

namespace Michel\Console;

interface OutputInterface
{
    public function error(string $message): void;
    public function write(string $message): void;
    public function writeln(string $message): void;
    public function setVerbose(bool $verbose): void;
    public function isVerbose(): bool;
}
