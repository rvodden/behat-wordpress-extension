<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Context\Traits;

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

    public function setCacheElement($cacheElement)
    {
        $this->cacheElement = $cacheElement;
    }
}
