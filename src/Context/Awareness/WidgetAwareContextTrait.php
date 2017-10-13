<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Awareness;

use Behat\Mink\Exception\DriverException;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Exception\UnsupportedDriverActionException;

trait WidgetAwareContextTrait
{
    public function getWidgetSidebar($sidebar_name)
    {
        return $this->getDriver()->widget->getSidebar($sidebar_name);
    }

    public function addWidgetToSidebar($widget_name, $sidebar, $args)
    {
        return $this->getDriver()->widget->addToSidebar($widget_name, $sidebar, $args);
    }
}
