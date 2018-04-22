<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Driver\Wpcli\Element;

use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\ThemeElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Wpcli\WpcliDriverInterface;

/**
 * WP-CLI driver element for themes.
 */
class ThemeElement extends BaseElement implements ThemeElementInterface
{
    /**
     * @var WpcliDriverInterface $driver
     */
    protected $driver;

    public function __construct(WpcliDriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Switch active theme.
     *
     * @param string $id   Theme name to switch to.
     * @param array  $args Not used.
     */
    public function change($id, $args = [])
    {
        $this->driver->wpcli('theme', 'activate', [$id]);
    }
}
