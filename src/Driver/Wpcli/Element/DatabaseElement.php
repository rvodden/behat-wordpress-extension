<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Driver\Wpcli\Element;

use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\DatabaseElementInterface;
use RuntimeException;
use PaulGibbs\WordpressBehatExtension\Driver\Wpcli\WpcliDriverInterface;

/**
 * WP-CLI driver element for manipulating the database directly.
 */
class DatabaseElement extends BaseElement implements DatabaseElementInterface
{

    /**
     *
     * @var WpcliDriverInterface $driver
     */
    var $driver;

    public function __construct(WpcliDriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Export site database.
     *
     * @param int   $id   Not used.
     * @param array $args
     *
     * @return string Path to the database dump.
     */
    public function export($id, $args = []) : string
    {
        $wpcli_args = ['--porcelain', '--add-drop-table'];

        if (! empty($args['path'])) {
            $file = tempnam($args['path'], 'wordhat');
            if ($file) {
                array_unshift($wpcli_args, $file);
            }
        };

        // Protect against WP-CLI changing the filename.
        $path = $this->driver->wpcli('db', 'export', $wpcli_args)['stdout'];
        if (! $path) {
            throw new RuntimeException('[W502] Could not export database');
        }

        return $path;
    }

    /**
     * Import site database.
     *
     * @param int   $id   Not used.
     * @param array $args
     */
    public function import($id, $args = [])
    {
        $this->driver->wpcli('db', 'import', [$args['path']]);

        /*
         * The WPPHP driver needs the WP cache flushed at this point. However
         * WPCLI appears to function without it. This is a note to remind us
         * so that if we see any strange behaviour with caching which is WPCLI
         * specific we might catch it. There's a discussion about it here:
         *
         * https://github.com/paulgibbs/behat-wordpress-extension/pull/150
         */
    }
}
