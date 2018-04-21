<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Context\Traits;

use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\PluginElementInterface;

/**
 * Provides driver agnostic logic (helper methods) relating to plugins.
 */
trait PluginAwareContextTrait
{
    use BaseAwarenessTrait;

    /**
     * @var PluginElementInterface
     */
    private $pluginElement;

    /**
     * Activate a plugin.
     *
     * @param string $plugin
     */
    public function activatePlugin(string $plugin)
    {
        $this->pluginElement->activate($plugin);
    }

    /**
     * Deactivate a plugin.
     *
     * @param string $plugin
     */
    public function deactivatePlugin(string $plugin)
    {
        $this->pluginElement->deactivate($plugin);
    }

    public function setPluginElement(PluginElementInterface $pluginElement)
    {
        $this->pluginElement = $pluginElement;
    }
}
