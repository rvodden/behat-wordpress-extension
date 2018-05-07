<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use RuntimeException;

/**
 * A specific driver can be specified for a scenario or example by using the @driver tag in the gherkin feature file.
 *
 * To make this work, we need to be able to switch drivers at the start of a scenario (if necessary). This
 * is done by the DriverListener which has its prepareWordpressDriver method called whenever a scenario or
 * example is started.
 *
 * It is the role of this class to make that happen. It is a Symfony compiler pass and is registered as such
 * by BehatWordpressExtension. During compilation it scans for services which are tagged with 'wordpress.event_subscriber',
 * and adds a call to the event_dispatchers 'addSubscriber' method for each service.
 *
 */
class EventSubscriberPass implements CompilerPassInterface
{
    /**
     * This method registers each service tagged with 'wordpress.event_subscriber' to the event_dispatcher service.
     *
     * Firstly it gets the event_dispatcher from the ServiceContainer by looking for a service named 'event_dispatcher'; this
     * service is automatically provided by the event_dispatcher bundle.
     *
     * Then it scans for 'wordpress.event_subscriber' tagged services. These services are tagged by WordpressBehatExtension,
     * which reads them (at the moment) from behat.yml.
     *
     * It checks if a priority has been configured on the service, if it has it uses that value, otherwise it uses 0.
     *
     * Finally it adds a call to the addSubsciber method at creation time of the event_dispatcher with a reference to the
     * subscriber service and the priority as arguments.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (! $container->hasDefinition('event_dispatcher')) {
            throw new RuntimeException('Event dispatcher cannot be found');
        }

        $dispatcher = $container->getDefinition('event_dispatcher');

        foreach ($container->findTaggedServiceIds('wordpress.event_subscriber') as $id => $attributes) {
            foreach ($attributes as $attribute) {
                $priority = isset($attribute['priority']) ? intval($attribute['priority']) : 0;

                $dispatcher->addMethodCall(
                    'addSubscriber',
                    [new Reference($id), $priority]
                );
            }
        }
    }
}
