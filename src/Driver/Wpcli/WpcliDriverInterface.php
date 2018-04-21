<?php
namespace PaulGibbs\WordpressBehatExtension\Driver\Wpcli;

interface WpcliDriverInterface
{
    public function wpcli(string $command, string $subcommand, array $raw_arguments = []): array;
}