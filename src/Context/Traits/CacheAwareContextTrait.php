<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Context\Traits;

use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\CacheElementInterface;

/**
 * Provides driver agnostic logic (helper methods) relating to caching.
 */
trait CacheAwareContextTrait
{
    use BaseAwarenessTrait;

    /**
     * var CacheElementInterface $cacheElement
     */
    protected $cacheElement;

    /**
     * Clear object cache.
     */
    public function clearCache()
    {
        $this->cacheElement->clear();
    }

    /**
     * Set the cacheElement
     *
     * @param CacheElementInterface $cacheElement
     */
    public function setCacheElement(CacheElementInterface $cacheElement)
    {
        $this->cacheElement = $cacheElement;
    }
}
