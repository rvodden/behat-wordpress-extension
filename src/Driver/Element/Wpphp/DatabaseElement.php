<?php
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Wpphp;

use RuntimeException;
use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;

/**
 * WP-API driver element for manipulating the database directly.
 */
class DatabaseElement extends BaseElement
{
    /**
     * Export site database.
     *
     * @param int   $id   Not used.
     * @param array $args
     *
     * @return string Path to the database dump.
     */
    public function get($id, $args = [])
    {
        if (empty($args['path'])) {
            $args['path'] = sys_get_temp_dir();
        }

        $path         = tempnam($args['path'], 'wordhat');
        $command_args = sprintf(
            '--no-defaults %1$s --add-drop-table --result-file=%2$s --host=%3$s --user=%4$s --password=%5$s',
            DB_NAME,
            escapeshellarg($path),
            escapeshellarg(DB_HOST),
            escapeshellarg(DB_USER),
            escapeshellarg(DB_PASSWORD)
        );

        $proc = proc_open(
            "/usr/bin/env mysqldump {$command_args}",
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

        if ($exit_code || $stderr) {
            throw new RuntimeException(
                sprintf(
                    "WP-PHP driver failure in database export for method %1\$s(): \n\t%2\$s\n(%3\$s)",
                    debug_backtrace()[1]['function'],
                    $stderr ?: $stdout,
                    $exit_code
                )
            );
        }

        return $stdout;
    }

    /**
     * Import site database.
     *
     * @param int   $id   Not used.
     * @param array $args
     */
    public function update($id, $args = [])
    {
        $command_args = sprintf(
            '--no-defaults --no-auto-rehash --host=%1$s --user=%2$s --password=%3$s --database=%4$s --execute=%5$s',
            escapeshellarg(DB_HOST),
            escapeshellarg(DB_USER),
            escapeshellarg(DB_PASSWORD),
            escapeshellarg(DB_NAME),
            escapeshellarg(sprintf(
                'SET autocommit = 0; SET unique_checks = 0; SET foreign_key_checks = 0; SOURCE %1$s; COMMIT;',
                $args['path']
            ))
        );

        $proc = proc_open(
            "/usr/bin/env mysql {$command_args}",
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

        if ($exit_code || $stderr) {
            throw new RuntimeException(
                sprintf(
                    "WP-PHP driver failure in database import for method %1\$s(): \n\t%2\$s\n(%3\$s)",
                    debug_backtrace()[1]['function'],
                    $stderr ?: $stdout,
                    $exit_code
                )
            );
        }
    }


    /*
     * Convenience methods.
     */

    /**
     * Alias of get().
     *
     * @see get()
     *
     * @param int   $id   Not used.
     * @param array $args
     *
     * @return string Path to the export file.
     */
    public function export($id, $args = [])
    {
        return $this->get($id, $args);
    }

    /**
     * Alias of update().
     *
     * @see update()
     *
     * @param int   $id   Not used.
     * @param array $args
     */
    public function import($id, $args = [])
    {
        $this->update($id, $args);
    }
}
