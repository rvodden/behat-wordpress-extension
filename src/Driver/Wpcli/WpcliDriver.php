<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Driver\Wpcli;

use UnexpectedValueException;



/**
 * Connect Behat to WordPress using WP-CLI.
 */
class WpcliDriver implements WpcliDriverInterface
{
    /**
     * Constructor.
     *
     * @param string      $alias  WP-CLI alias. This or $path must be not falsey.
     * @param string      $url    WordPress site URL.
     * @param string|null $binary Path to the WP-CLI binary.
     * @param string      $path   Optional. Path to WordPress site's files. This or $alias must be not falsey.
     */
    public function __construct(string $alias, string $url, string $binary = null, string $path = '')
    {
        $this->alias  = ltrim($alias, '@');
        $this->path   = $path;
        $this->url    = rtrim(filter_var($url, FILTER_SANITIZE_URL), '/');
        $this->binary = $binary;
    }


    public function wpcli(string $command, string $subcommand, array $raw_arguments = []): array
    {
        $arguments = implode(' ', $raw_arguments);
        $config    = sprintf('--url=%s', escapeshellarg($this->url));

        if ($this->path) {
            $config .= sprintf(' --path=%s', escapeshellarg($this->path));
        }

        // Support WP-CLI environment aliases.
        if ($this->alias) {
            $config = "@{$this->alias}";
        }

        $command = "{$this->binary} {$config} --no-color {$command} {$subcommand} {$arguments}";

        // Query WP-CLI.
        $proc = proc_open(
            $command,
            array(
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ),
            $pipes
            );

        $stdout = trim(stream_get_contents($pipes[1]));
        $stderr = trim(stream_get_contents($pipes[2]));
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exit_code = proc_close($proc);

        // Sometimes the error message is in stderr.
        if (! $stdout && $stderr) {
            $stdout = $stderr;
        }

        if ($exit_code || strpos($stdout, 'Warning: ') === 0 || strpos($stdout, 'Error: ') === 0) {
            if ($exit_code === 255 && ! $stdout) {
                $stdout = 'Unable to connect to server via SSH. Is it on?';
            }

            throw new UnexpectedValueException(
                sprintf(
                    "[W102] WP-CLI driver failure in method %1\$s():\n\n%2\$s.\n\nTried to run: %3\$s\n(%4\$s)",
                    debug_backtrace()[1]['function'],
                    $stdout,
                    $command,
                    $exit_code
                    )
                );
        }

        return compact('stdout', 'exit_code');
    }
}