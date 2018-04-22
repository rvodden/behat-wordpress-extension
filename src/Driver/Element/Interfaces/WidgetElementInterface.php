<?php
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces;

use UnexpectedValueException;

/**
 * WidgetElementInterface
 *
 */
interface WidgetElementInterface
{

    /**
     * Adds a widget to the sidebar with the specified arguments
     *
     * @param string $widget_name The ID base of the widget (e.g. 'meta', 'calendar'). Case insensitive.
     * @param string $sidebar_id  The ID of the sidebar to the add the widget to
     * @param array  $args        Associative array of widget settings for this widget
     */
    public function addToSidebar($widget_name, $sidebar_id, $args);

    /**
     * Gets a sidebar ID from its human-readable name
     *
     * @param string $sidebar_name The name of the sidebar (e.g. 'Footer', 'Widget Area', 'Right Sidebar')
     *
     * @throws UnexpectedValueException If the sidebar is not registered
     *
     * @return string The sidebar ID
     */
    public function getSidebar($sidebar_name);
}
