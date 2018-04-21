<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Compiler;

use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\PluginElementInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * WordpressBehatExtension container compilation pass.
 */
class DriverElementPass implements CompilerPassInterface
{
    /**
     * Modify the container before Symfony compiles it.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $container->registerForAutoconfiguration(PluginElementInterface::class)
            ->addTag('wordpress.driver.element.plugin');
    }
}
