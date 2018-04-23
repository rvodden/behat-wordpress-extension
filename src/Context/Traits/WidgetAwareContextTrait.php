<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Context\Traits;

use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\WidgetElementInterface;

trait WidgetAwareContextTrait
{
    use BaseAwarenessTrait;

    /**
     *
     * @var WidgetElementInterface $widgetElement;
     */
    protected $widgetElement;

    /**
     * Gets a sidebar ID from its human-readable name.
     *
     * @param string $sidebar_name The name of the sidebar (e.g. 'Footer', 'Widget Area', 'Right Sidebar').
     *
     * @return string The sidebar ID.
     */
    public function getWidgetSidebar(string $sidebar_name): string
    {
        return $this->widgetElement->getSidebar($sidebar_name);
    }

    /**
     * Adds a widget to the sidebar with the specified arguments.
     *
     * @param string $widget_name The ID base of the widget (e.g. 'meta', 'calendar'). Case insensitive.
     * @param string $sidebar_id  The ID of the sidebar to the add the widget to.
     * @param array  $args        Associative array of widget settings for this widget.
     */
    public function addWidgetToSidebar(string $widget_name, string $sidebar_id, array $args)
    {
        $this->widgetElement->addToSidebar($widget_name, $sidebar_id, $args);
    }

    public function setWidgetElement(WidgetElementInterface $widgetElement)
    {
        $this->widgetElement = $widgetElement;
    }
}
