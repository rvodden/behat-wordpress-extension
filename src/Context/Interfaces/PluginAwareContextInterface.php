<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Interfaces;

use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\PluginElementInterface;

/**
 *
 * @author voddenr
 *
 */
interface PluginAwareContextInterface
{
    public function activatePlugin(string $plugin);
    public function deactivatePlugin(string $plugin);

    public function setPluginElement(PluginElementInterface $pluginElement);
}
