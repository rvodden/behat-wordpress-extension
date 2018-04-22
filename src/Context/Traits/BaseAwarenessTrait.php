<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Context\Traits;


/**
 * Parent class for all awareness traits providing common code.
 */
trait BaseAwarenessTrait
{
    /**
     * Get active WordPress Driver.
     *
     * @param string $name Optional. Name of specific driver to retrieve.
     *
     */
    public abstract function getDriver(string $name = '');
}
