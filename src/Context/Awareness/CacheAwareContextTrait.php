<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Awareness;

trait CacheAwareContextTrait {
    /**
     * Clear object cache.
     */
    public function clearCache()
    {
        $this->getDriver()->cache->clear();
    }
}