<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces;

interface PluginElementInterface
{
    /**
     * Activate a plugin.
     *
     * @param string $id   Plugin name to activate.
     * @param array  $args Optional data used to update an object.
     */
    public function activate($id, $args = []);

    /**
     * Deactivate a plugin.
     *
     * @param string $id   Plugin name to deactivate.
     * @param array  $args Optional data used to update an object.
     */
    public function deactivate($id, $args = []);
}
