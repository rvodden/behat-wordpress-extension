<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Awareness;

use Behat\Mink\Exception\DriverException;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use UnexpectedValueException;

trait WidgetAwareContextTrait
{
    use BaseAwarenessTrait;

    /**
     * Gets a sidebar ID from its human-readable name
     *
     * @param string $sidebar_name The name of the sidebar (e.g. 'Footer', 'Widget Area', 'Right Sidebar')
     *
     * @throws UnexpectedValueException If the sidebar is not registered
     *
     * @return string The sidebar ID
     */
    public function getWidgetSidebar($sidebar_name)
    {
        return $this->getDriver()->widget->getSidebar($sidebar_name);
    }

    /**
     * Adds a widget to the sidebar with the specified arguments
     *
     * @param string $widget_name The ID base of the widget (e.g. 'meta', 'calendar'). Case insensitive.
     * @param string $sidebar_id  The ID of the sidebar to the add the widget to
     * @param array  $args        Associative array of widget settings for this widget
     *
     * @throws UnexpectedValueException If the widget or sidebar does not exist.
     */
    public function addWidgetToSidebar($widget_name, $sidebar, $args)
    {
        $this->getDriver()->widget->addToSidebar($widget_name, $sidebar, $args);
    }
}
