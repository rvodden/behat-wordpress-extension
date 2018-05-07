<?php
declare(strict_types = 1);
namespace PaulGibbs\WordpressBehatExtension\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Behat\Testwork\ServiceContainer\ServiceProcessor;
use PaulGibbs\WordpressBehatExtension\Compiler\DriverElementPass;
use PaulGibbs\WordpressBehatExtension\Compiler\DriverPass;
use PaulGibbs\WordpressBehatExtension\Compiler\EventSubscriberPass;
use PaulGibbs\WordpressBehatExtension\Driver\DriverManagerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use function Symfony\Component\Config\Definition\Builder\NodeBuilder\scalarNode;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Loader\FileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Main part of the Behat extension.
 */
class WordpressBehatExtension implements ExtensionInterface
{

    /**
     *
     * @var ServiceProcessor
     */
    protected $processor;

    /**
     *
     * @var string
     */
    const CONFIG_KEY = 'wordpress';

    /**
     * Constructor.
     *
     * @param ServiceProcessor|null $processor
     *            Optional.
     */
    public function __construct(ServiceProcessor $processor = null)
    {
        $this->processor = $processor ?: new ServiceProcessor();
    }

    /**
     * Returns the extension config key.
     *
     * @return string
     */
    public function getConfigKey(): string
    {
        return self::CONFIG_KEY;
    }

    /**
     * Initialise extension.
     *
     * This method is called immediately after all extensions are activated but
     * before any extension `configure()` method is called. This allows extensions
     * to hook into the configuration of other extensions providing such an
     * extension point.
     *
     * In this case WordHat needs the PageObjectExtension, so we activate it here.
     *
     * @param ExtensionManager $extension_manager
     */
    public function initialize(ExtensionManager $extension_manager)
    {
        $extension_manager->activateExtension('SensioLabs\Behat\PageObjectExtension');
    }

    /**
     * Declare configuration options for the extension.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
            // Common settings.
            ->enumNode('default_driver')
                // "wpapi" is for backwards compatibility; means "wpphp".
                ->values(['wpcli','wpapi','wpphp','blackbox'])
                ->defaultValue('wpcli')
            ->end()
            ->scalarNode('path')
                ->defaultValue('')
            ->end()

        // WordPress' "siteurl" option.
            ->scalarNode('site_url')->defaultValue('%mink.base_url%')->end()

            // Account roles -> username/password.
            ->arrayNode('users')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('admin')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('username')
                                ->defaultValue('admin')
                            ->end()
                            ->scalarNode('password')
                                ->defaultValue('admin')
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('editor')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('username')
                                ->defaultValue('editor')
                            ->end()
                            ->scalarNode('password')
                                ->defaultValue('editor')
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('author')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('username')
                                ->defaultValue('author')
                            ->end()
                            ->scalarNode('password')
                                ->defaultValue('author')
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('contributor')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('username')
                                ->defaultValue('contributor')
                            ->end()
                            ->scalarNode('password')
                                ->defaultValue('contributor')
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('subscriber')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('username')
                                ->defaultValue('subscriber')
                            ->end()
                            ->scalarNode('password')
                                ->defaultValue('subscriber')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()

            // WP-CLI driver.
            ->arrayNode('wpcli')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('alias')
                        ->end()
                        ->scalarNode('binary')
                            ->defaultValue('wp')
                        ->end()
                    ->end()
                ->end()

            // WordPress PHP driver.
            ->arrayNode('wpphp')
                ->addDefaultsIfNotSet()
                    ->children()
                    ->end()
                ->end()

            // Blackbox driver.
            ->arrayNode('blackbox')
                ->addDefaultsIfNotSet()
                    ->children()
                    ->end()
                ->end()

            // Database management.
            ->arrayNode('database')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('restore_after_test')
                        ->defaultFalse()
                    ->end()
                    ->scalarNode('backup_path')
                    ->end()
                ->end()
            ->end()

            // Permalink patterns.
            ->arrayNode('permalinks')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('author_archive')
                        ->defaultValue('author/%s/')
                    ->end()
                ->end()
            ->end()

            // Internal use only. Don't use it. Or else.
            ->arrayNode('internal')
                ->addDefaultsIfNotSet()
                ->end()
            ->end()
        ->end();
    }

    /**
     * Adds configuration which is common to all the drivers to the provided NodeBuilder.
     *
     * @param NodeBuilder $nodeBuilder
     * @param string $role
     * @return boolean
     */
    protected function addCommonSettings(NodeBuilder $nodeBuilder)
    {
        $nodeBuilder->enumNode('default_driver')
            ->values([
            'wpcli',
            'wpapi',
            'wpphp',
            'blackbox'
        ])
            ->defaultValue('wpcli')
            ->end();

        $nodeBuilder->scalarNode('path')
            ->defaultValue('')
            ->end();

        scalarNode('site_url')->defaultValue('%mink.base_url%')->end();
    }

    /**
     * Load extension services into ServiceContainer.
     *
     * @param ContainerBuilder $container
     * @param array $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $container->registerForAutoconfiguration(DriverManagerInterface::class)->addTag('wordpress.driver');

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/config'));
        $loader->load('services.yml');

        $this->setParameters($container, $config);

        $container->addCompilerPass(new DriverPass());
        $container->addCompilerPass(new DriverElementPass());
        $container->addCompilerPass(new EventSubscriberPass());
    }

    /**
     * Register settings with symfony
     *
     * @param ContainerBuilder $container
     * @param array $config
     */
    protected function setParameters(ContainerBuilder $container, array $config)
    {
        // Backwards compatibility for pre-1.0. Will be removed in 2.0.
        if ($config['default_driver'] === 'wpapi') {
            $config['default_driver'] = 'wpphp';
        }

        $container->setParameter('wordpress.default_driver', $config['default_driver']);
        $container->setParameter('wordpress.path', $config['path']);
        $container->setParameter('wordpress.parameters', $config);
    }

    /**
     * Load settings for the WP-CLI driver.
     *
     * @param FileLoader $loader
     * @param ContainerBuilder $container
     * @param array $config
     */
    protected function setupWpcliDriver(FileLoader $loader, ContainerBuilder $container, array $config)
    {
        if (! isset($config['wpcli'])) {
            return;
        }
        // $loader->load('drivers/wpcli.yml');
        $config['wpcli']['alias'] = isset($config['wpcli']['alias']) ? $config['wpcli']['alias'] : '';
        $container->setParameter('wordpress.driver.wpcli.alias', $config['wpcli']['alias']);
        $config['wpcli']['path'] = isset($config['path']) ? $config['path'] : '';
        $container->setParameter('wordpress.driver.wpcli.path', $config['path']);
        $config['wpcli']['binary'] = isset($config['wpcli']['binary']) ? $config['wpcli']['binary'] : null;
        $container->setParameter('wordpress.driver.wpcli.binary', $config['wpcli']['binary']);
    }

    /**
     * Load settings for the WordPress PHP driver.
     *
     * @param FileLoader $loader
     * @param ContainerBuilder $container
     * @param array $config
     */
    protected function setupWpphpDriver(FileLoader $loader, ContainerBuilder $container, array $config)
    {
        $loader->load('drivers/wpphp.yml');

        $config['wpphp']['path'] = isset($config['path']) ? $config['path'] : '';
        $container->setParameter('wordpress.driver.path', $config['wpphp']['path']);
    }

    /**
     * Load settings for the blackbox driver.
     *
     * @param FileLoader $loader
     * @param ContainerBuilder $container
     * @param array $config
     */
    protected function setupBlackboxDriver(FileLoader $loader, ContainerBuilder $container, array $config)
    {
        if (! isset($config['blackbox'])) {
            return;
        }

        $loader->load('drivers/blackbox.yml');
    }

    /**
     * Modify the container before Symfony compiles it.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $this->setPageObjectNamespaces($container);
        $this->injectSiteUrlIntoPageObjects($container);
        $this->listServicesAndTags($container);
    }

    /**
     * Helper method for debugging the compiler passes.
     *
     * When called all the services are sent to stdout.
     */
    private function listServicesAndTags(ContainerBuilder $container)
    {
        $serviceIds = $container->getServiceIds();

        foreach ($serviceIds as $serviceId) {
            echo "\tServiceId: " . $serviceId, ',';
            try {
                $definition = $container->getDefinition($serviceId);
                $class = $definition->getClass();
                echo 'Class: ' . $class, ',';
                $tags = $definition->getTags();
                if ($tags) {
                    $tagInformation = array();
                    foreach ($tags as $tagName => $tagData) {
                        echo "[$tagName";
                        foreach ($tagData as $tagParameters) {
                            $parameters = array_map(function ($key, $value) {
                                return sprintf('%s: %s', $key, $value);
                            }, array_keys($tagParameters), array_values($tagParameters));

                            $parameters = implode(', ', $parameters);
                            if ('' !== $parameters) {
                                $tagInformation[] = sprintf('(%s)', $parameters);
                            }
                        }
                        echo implode(',', $tagInformation) . ']';
                    }
                }
                echo "\n";
            } catch (ServiceNotFoundException $e) {
                echo "\n";
                continue;
            }
            // print_r(array_merge( class_parents("$class"), class_implements("$class")));
        }
    }

    /**
     * Set up custom Context class.
     *
     * `behat --init` creates an inital Context class. Here, we switch the template used for that.
     *
     * @param ContainerBuilder $container
     */
    protected function processClassGenerator(ContainerBuilder $container)
    {
        echo __FUNCTION__ . "\n";
        $definition = new Definition('PaulGibbs\WordpressBehatExtension\Context\ContextClass\ClassGenerator');
        $container->setDefinition(ContextExtension::CLASS_GENERATOR_TAG . '.simple', $definition);
    }

    /**
     * Tell Page Object Extension the namespace of our page objects
     *
     * @param ContainerBuilder $container
     */
    protected function setPageObjectNamespaces(ContainerBuilder $container)
    {
        // Append our namespaces as earlier namespaces take precedence.
        $pages = $container->getParameter('sensio_labs.page_object_extension.namespaces.page');
        $pages[] = 'PaulGibbs\WordpressBehatExtension\PageObject';

        $elements = $container->getParameter('sensio_labs.page_object_extension.namespaces.element');
        $elements[] = 'PaulGibbs\WordpressBehatExtension\PageObject\Element';

        $container->setParameter('sensio_labs.page_object_extension.namespaces.page', $pages);
        $container->setParameter('sensio_labs.page_object_extension.namespaces.element', $elements);
    }

    /**
     * Adds the WordPress site url as a page parameter into page objects.
     *
     * @param ContainerBuilder $container
     */
    protected function injectSiteUrlIntoPageObjects(ContainerBuilder $container)
    {
        $page_parameters = $container->getParameter('sensio_labs.page_object_extension.page_factory.page_parameters');
        $page_parameters = $container->getParameterBag()->resolveValue($page_parameters);
        $page_parameters['site_url'] = $container->getParameter('wordpress.parameters')['site_url'];
        $container->setParameter('sensio_labs.page_object_extension.page_factory.page_parameters', $page_parameters);
    }
}
