<?php

namespace PaulGibbs\WordpressBehatExtension\Context\Awareness;

trait PluginAwareContextTrait{
    /**
     * Activate a plugin.
     *
     * @param string $plugin
     */
    public function activatePlugin($plugin)
    {
        $this->getDriver()->plugin->activate($plugin);
    }
    
    /**
     * Deactivate a plugin.
     *
     * @param string $plugin
     */
    public function deactivatePlugin($plugin)
    {
        $this->getDriver()->plugin->deactivate($plugin);
    }
    
}