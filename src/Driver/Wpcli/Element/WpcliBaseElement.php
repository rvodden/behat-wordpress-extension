<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Driver\Wpcli\Element;

use PaulGibbs\WordpressBehatExtension\Driver\Wpcli\WpcliDriverInterface;

/**
 * Super element for all WpCLI elements so that its possible
 * to refer to them all in a type safe way.
 */
abstract class WpcliBaseElement
{
    /*
     * @var WpcliExecutor
     */
    protected $driver;

    public function __construct(WpcliDriverInterface $driver)
    {
        $this->driver = $driver;
    }

    protected function getDriver() : WpcliDriverInterface
    {
        return $this->driver;
    }
}
