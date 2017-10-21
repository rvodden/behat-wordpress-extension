<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Awareness;

/**
 * Provides driver agnostic logic (helper methods) relating to caching.
 */
trait CacheAwareContextTrait
{
    use BaseAwarenessTrait;

    /**
     * Clear object cache.
     */
    public function clearCache()
    {
        $this->getDriver()->cache->clear();
    }
}
