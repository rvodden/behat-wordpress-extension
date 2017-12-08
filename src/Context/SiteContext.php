<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

/**
 * Provides step definitions for managing plugins and themes.
 */
class SiteContext extends RawWordpressContext
{
    use Traits\CacheAwareContextTrait, Traits\PluginAwareContextTrait, Traits\ThemeAwareContextTrait;

    /**
     * Clear object cache.
     *
     * Example: When the cache is cleared
     * Example: Given the cache has been cleared
     *
     * @When the cache is cleared
     * @Given the cache has been cleared
     */
    public function cacheIsCleared()
    {
        $this->clearCache();
    }

    /**
     * Active a plugin.
     *
     * Example: When I activate the "hello" plugin
     * Example: Given the "hello" plugin is active
     *
     * @Given the :plugin plugin is active
     * @When I activate the :plugin plugin
     */
    public function iActivateThePlugin($plugin)
    {
        $this->activatePlugin($plugin);
    }

    /**
     * Deactivate a plugin.
     *
     * Example: When I deactivate the "hello" plugin
     *
     * @When I deactivate the :plugin plugin
     */
    public function iDeactivateThePlugin($plugin)
    {
        $this->deactivatePlugin($plugin);
    }
}
