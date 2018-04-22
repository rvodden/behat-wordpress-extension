<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Driver\Wpcli;

use PaulGibbs\WordpressBehatExtension\Driver\DriverManagerInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\CacheElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\CommentElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\ContentElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\DatabaseElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\PluginElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\TermElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\ThemeElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\UserElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\WidgetElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Wpcli\Element\CacheElement;
use PaulGibbs\WordpressBehatExtension\Driver\Wpcli\Element\CommentElement;
use PaulGibbs\WordpressBehatExtension\Driver\Wpcli\Element\ContentElement;
use PaulGibbs\WordpressBehatExtension\Driver\Wpcli\Element\DatabaseElement;
use PaulGibbs\WordpressBehatExtension\Driver\Wpcli\Element\PluginElement;
use PaulGibbs\WordpressBehatExtension\Driver\Wpcli\Element\TermElement;
use PaulGibbs\WordpressBehatExtension\Driver\Wpcli\Element\ThemeElement;
use PaulGibbs\WordpressBehatExtension\Driver\Wpcli\Element\UserElement;
use PaulGibbs\WordpressBehatExtension\Driver\Wpcli\Element\WidgetElement;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use RuntimeException;

/**
 * Connect WordHat to WordPress using WP-CLI.
 */
class WpcliManager implements DriverManagerInterface
{
    /**
     * @var string
     */
    const SHORTNAME = 'wpcli';

    /**
     * @var bool
     */
    var $is_bootstrapped = false;

    /**
     * @var WpcliDriverInterface $driver
     */
    var $driver;

    /**
     * Constructor
     */
    public function __construct(WpcliDriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * setParameters
     *
     * @param ContainerBuilder $container
     * @param array $config
     */
    public static function setParameters(ContainerBuilder $container, array $config)
    {
        if (! isset($config['wpcli'])) {
            throw new RuntimeException("Cannot find wpcli configuration in behat.yml\n");
        }

        $container->setAlias(CacheElementInterface::class, CacheElement::class);
        $container->setAlias(CommentElementInterface::class, CommentElement::class);
        $container->setAlias(ContentElementInterface::class, ContentElement::class);
        $container->setAlias(DatabaseElementInterface::class, DatabaseElement::class);
        $container->setAlias(PluginElementInterface::class, PluginElement::class);
        $container->setAlias(TermElementInterface::class, TermElement::class);
        $container->setAlias(ThemeElementInterface::class, ThemeElement::class);
        $container->setAlias(UserElementInterface::class, UserElement::class);
        $container->setAlias(WidgetElementInterface::class, WidgetElement::class);

        $container->setAlias(WpcliDriverInterface::class, WpcliDriver::class);

        $definition = $container->getDefinition(WpcliDriver::class);

        $config['wpcli']['alias'] = isset($config['wpcli']['alias']) ? $config['wpcli']['alias'] : '';
        $container->setParameter('wordpress.driver.wpcli.alias', $config['wpcli']['alias']);

        $config['wpcli']['binary'] = isset($config['wpcli']['binary']) ? $config['wpcli']['binary'] : null;
        $container->setParameter('wordpress.driver.wpcli.binary', $config['wpcli']['binary']);

        $definition->addArgument('%wordpress.driver.wpcli.alias%'); // $alias
        $definition->addArgument('%mink.base_url%'); // $url
        $definition->addArgument('%wordpress.driver.wpcli.binary%'); // $binary
        $definition->addArgument('%wordpress.path%'); // $path
    }

    public static function getShortName(): string
    {
        return get_called_class()::SHORTNAME;
    }

    public function isBootstrapped(): bool
    {
        return $this->is_bootstrapped;
    }

    public function bootstrap()
    {
        $this->driver->bootstrap();
        $this->is_bootstrapped = true;
    }
}
