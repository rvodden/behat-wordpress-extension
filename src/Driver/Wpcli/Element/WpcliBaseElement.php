<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Driver\Wpcli\Element;

use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;
use PaulGibbs\WordpressBehatExtension\Driver\Wpcli\WpcliDriver;

/**
 * Super element for all WpCLI elements so that its possible
 * to refer to them all in a type safe way.
 */
abstract class WpcliBaseElement extends BaseElement
{
    /* TODO: This class may well not be necessary */

    public function __construct(WpcliDriver $driver)
    {
        parent::__construct($driver);
    }
}
