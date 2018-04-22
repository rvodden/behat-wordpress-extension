<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Driver\Wpcli\Element;

use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\PluginElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Wpcli\WpcliDriverInterface;

/**
 * WP-CLI driver element for plugins.
 */
class PluginElement extends BaseElement implements PluginElementInterface
{
    /**
     * @var WpcliDriverInterface $driver
     */
    var $driver;

    /**
     * @var WpcliDriverInterface $driver
     */
    public function __construct(WpcliDriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Activate or deactivate specified plugin.
     *
     * @param string $id   Plugin name.
     * @param array  $args Optional data used to update an object.
     */
    protected function update($id, $args = [])
    {
        $this->driver->wpcli('plugin', $args['status'], [$id]);
    }

    /*
     * Convenience methods.
     */

    /**
     * Alias of update().
     *
     * @see update()
     *
     * @param string $id   Plugin name to activate.
     * @param array  $args Optional data used to update an object.
     */
    public function activate($id, $args = [])
    {
        $this->update($id, ['status' => 'activate']);
    }

    /**
     * Alias of update().
     *
     * @see update()
     *
     * @param string $id   Plugin name to deactivate.
     * @param array  $args Optional data used to update an object.
     */
    public function deactivate($id, $args = [])
    {
        $this->update($id, ['status' => 'deactivate']);
    }
}
