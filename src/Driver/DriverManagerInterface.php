<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Driver;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * WordPress driver interface.
 *
 * A driver represents and manages the connection between the Element classes and a WordPress site.
 */
interface DriverManagerInterface
{
    /**
     * Has the driver has been bootstrapped?
     *
     * @return bool
     */
    public function isBootstrapped(): bool;

    /**
     * Set up anything required for the driver.
     *
     * Called when the driver is used for the first time.
     *
     * @return void
     */
    public function bootstrap();

    /**
     * Register the driver with symfony.
     *
     * Called when the driver is used for the first time.
     *
     * @return void
     */
    public static function setParameters(ContainerBuilder $container, array $config);

    /**
     * Returns the short name of the driver (e.g. 'wcli'
     *
     * Called when the driver is registered with symfony.
     *
     * @return string
     */
    public static function getShortName() : string;
}
