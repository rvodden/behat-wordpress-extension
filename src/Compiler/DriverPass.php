<?php
declare(strict_types = 1);
namespace PaulGibbs\WordpressBehatExtension\Compiler;

use PaulGibbs\WordpressBehatExtension\Driver\DriverManagerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use RuntimeException;

/**
 * WordpressBehatExtension container compilation pass.
 */
class DriverPass implements CompilerPassInterface
{

    /**
     * Modify the container before Symfony compiles it.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        // Get the WordpressDriverManager
        $wordpress = $container->getDefinition('wordpress.wordpress');
        if (! $wordpress) {
            throw new RuntimeException("\tNo type with tag 'wordpress.worpress' found\n");
        }
        $type = $wordpress->getClass();

        $config = $this->getConfigs($container);

        // Grab the default driver name from the config
        $defaultDriverName = $container->getParameter('wordpress.wordpress.default_driver');

        // Tell WordpressDriverManager about the default driver name
        $wordpress->addMethodCall('setDefaultDriverName', [
            $defaultDriverName
        ]);

        foreach ($container->findTaggedServiceIds('wordpress.driver') as $id => $attributes) {
            // Grab the definition
            $definition = $container->getDefinition($id);
            $class = $definition->getClass();

            // configure the driver
            $driverName = call_user_func($class . '::setParameters', $container, $config);
            $driverName = call_user_func($class . '::getShortName');

            // short name
            $wordpress->addMethodCall('registerDriver', [
                $driverName,
                new Reference($id)
            ]);

            if ($driverName === $defaultDriverName) {
                $container->setAlias(DriverManagerInterface::class, $id);
            }
        }
    }

    /**
     * grab the configuration from the container builder
     */
    protected function getConfigs(ContainerBuilder $container)
    {
        return $container->getParameter('wordpress.parameters');
    }
}
