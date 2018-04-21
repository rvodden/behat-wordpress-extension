<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Driver\Wpcli;

use PaulGibbs\WordpressBehatExtension\Driver\DriverManagerInterface;
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
    const SHORTNAME = "wpcli";

    public static function setParameters(ContainerBuilder $container, array $config)
    {
        if (! isset($config['wpcli'])) {
            throw RuntimeException("Cannot find wpcli configuration in behat.yml\n");
        }

        $definition = $container->getDefinition(WpcliDriver::class);

        $config['wpcli']['alias'] = isset($config['wpcli']['alias']) ? $config['wpcli']['alias'] : '';
        $container->setParameter('wordpress.driver.wpcli.alias', $config['wpcli']['alias']);
        $definition->addArgument('%wordpress.driver.wpcli.alias%');

        $config['wpcli']['path'] = isset($config['path']) ? $config['path'] : '';
        $container->setParameter('wordpress.driver.wpcli.path', $config['path']);
        $definition->addArgument('%wordpress.driver.wpcli.path%');

        $config['wpcli']['binary'] = isset($config['wpcli']['binary']) ? $config['wpcli']['binary'] : null;
        $container->setParameter('wordpress.driver.wpcli.binary', $config['wpcli']['binary']);
        $definition->addArgument('%wordpress.driver.wpcli.binary%');
    }

    public static function getShortName(): string
    {
        return get_called_class()::SHORTNAME;
    }

    public function isBootstrapped(): bool
    {

    }

    public function bootstrap()
    {
        $version = '';
        preg_match('#^WP-CLI (.*)$#', $this->wpcli('cli', 'version')['stdout'], $match);
        if (! empty($match)) {
            $version = array_pop($match);
        }
        if (! version_compare($version, '1.5.0', '>=')) {
            throw new RuntimeException('[W100] Your WP-CLI is too old; version 1.5.0 or newer is required.');
        }
        $status = $this->wpcli('core', 'is-installed')['exit_code'];
        if ($status !== 0) {
            throw new RuntimeException('[W101] WordPress does not seem to be installed. Check "path" and/or "alias" settings in behat.yml.');
        }
        putenv('WP_CLI_STRICT_ARGS_MODE=1');
        $this->is_bootstrapped = true;
    }

}