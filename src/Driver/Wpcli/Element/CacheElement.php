<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Driver\Wpcli\Element;


use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\CacheElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Wpcli\WpcliDriverInterface;

/**
 * WP-CLI driver element for site cache.
 */
class CacheElement implements CacheElementInterface
{
    /**
     *
     * @var WpcliDriverInterface $driver
     */
    var $driver;

    public function __construct(WpcliDriverInterface $driver) {
        $this->driver = $driver;
    }

    /**
     * Clear object cache.
     */
    public function clear()
    {
        $this->driver->wpcli('cache', 'flush');
    }
}
