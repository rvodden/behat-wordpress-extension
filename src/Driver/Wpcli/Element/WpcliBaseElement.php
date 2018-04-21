<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Driver\Wpcli\Element;

use PaulGibbs\WordpressBehatExtension\Driver\Wpcli\WpcliDriver;

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

    public function __construct(WpcliDriver $driver)
    {
        parent::__construct($driver);
    }

    protected function getDriver() : WpcliDriver
    {
        return $driver;
    }
}
