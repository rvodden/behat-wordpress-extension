<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Driver\Element;

use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\PluginElementInterface;

/**
 * Common base class for WordPress driver elements.
 *
 * An element represents a distinct item that a driver promises to implement.
 */
abstract class BasePluginElement implements PluginElementInterface
{
}
