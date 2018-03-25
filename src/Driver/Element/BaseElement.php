<?php
declare(strict_types = 1);
namespace PaulGibbs\WordpressBehatExtension\Driver\Element;

use PaulGibbs\WordpressBehatExtension\Driver\DriverInterface;

/**
 * Common base class for WordPress driver elements.
 *
 * An element represents a distinct item that a driver promises to implement.
 */
abstract class BaseElement implements ElementInterface
{

    /**
     * WordPress driver.
     *
     * @var DriverInterface
     */
    private $driver;

    /**
     * Constructor.
     *
     * @param DriverInterface $drivers
     */
    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }
    
    protected function getDriver() : DriverInterface
    {
        return $this->driver;
    }
}
