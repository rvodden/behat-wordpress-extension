<?php
declare(strict_types = 1);
namespace PaulGibbs\WordpressBehatExtension\Compiler;

use PaulGibbs\WordpressBehatExtension\Driver\DriverManagerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * This class is a Symfony compiler pass. It is registered with
 * Symfony by BehatWordpressExtension.
 *
 * During compilation Symfony will call the process method.
 *
 * Initially it grabs the wordhat configuration from the ContainerBuilder. This
 * was previously saved by BehatWordpressExtension, which in turn was read from
 * behat.yml.
 *
 * Then it scans the existing services for those tagged with 'wordpress.driver'.
 * For each tagged service it:
 * 1) Grabs the shortname
 * 2) Executes the bootstrap method on the driver class
 * 3) Checks if shortname matched the default driver in the configuration. If it does is:
 *   - Binds the driverManager class to the DriverManagerInterface
 *   - Calls the setParameters method on the driver class which loads the driver configuration
 *     and binds in each of the elements.
 *
 * @see PaulGibbs\WordpressBehatExtension\ServiceContainer\BehatWordpressExtension
 */
class DriverPass implements CompilerPassInterface
{
    /**
     * Entrypoint for the Symfony compiler path
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $config = $this->getConfigs($container);

        // Grab the default driver name from the config
        $defaultDriverName = $container->getParameter('wordpress.default_driver');

        // Tell WordpressDriverManager about the default driver name
        //$wordpress->addMethodCall('setDefaultDriverName', [
        //   $defaultDriverName
        //]);

        foreach ($container->findTaggedServiceIds('wordpress.driver') as $id => $attributes) {
            // Grab the definition
            $definition = $container->getDefinition($id);
            $class = $definition->getClass();

            /* TODO: The setParameters method below should be split into an activate
             * method and a setParameters method. The setParameters part should be called
             * here.
             */

            $driverName = call_user_func($class . '::getShortName');

            // bootstrap the driver at the point it is created
            $definition->addMethodCall('bootstrap');

            if ($driverName === $defaultDriverName) {
                $container->setAlias(DriverManagerInterface::class, $id);
                $driverName = call_user_func($class . '::setParameters', $container, $config);
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
