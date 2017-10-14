<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\RawMinkContext;
use PaulGibbs\WordpressBehatExtension\WordpressDriverManager;
use SensioLabs\Behat\PageObjectExtension\Context\PageObjectAware;

/**
 * Base Behat context.
 *
 * Does not contain any step defintions.
 */
class RawWordpressContext extends RawMinkContext implements WordpressAwareInterface, SnippetAcceptingContext, PageObjectAware
{
    use PageObjectContextTrait;

    /**
     * WordPress driver manager.
     *
     * @var WordpressDriverManager
     */
    protected $wordpress;

    /**
     * WordPress parameters.
     *
     * @var array
     */
    protected $wordpress_parameters;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * Build URL, based on provided path.
     *
     * @param string $path
     *            Relative or absolute URL.
     *
     * @return string
     */
    public function locatePath($path)
    {
        if (stripos($path, 'http') === 0) {
            return $path;
        }

        $url = $this->getMinkParameter('base_url');

        if (strpos($path, 'wp-admin') !== false || strpos($path, '.php') !== false) {
            $url = $this->getWordpressParameter('site_url');
        }

        return rtrim($url, '/') . '/' . ltrim($path, '/');
    }

    /**
     * Set WordPress instance.
     *
     * @param WordpressDriverManager $wordpress
     */
    public function setWordpress(WordpressDriverManager $wordpress)
    {
        $this->wordpress = $wordpress;
    }

    /**
     * Get WordPress instance.
     *
     * @return WordpressDriverManager
     */
    public function getWordpress()
    {
        return $this->wordpress;
    }

    /**
     * Set parameters provided for WordPress.
     *
     * IMPORTANT: this only sets the variable for the current Context!
     * Each Context exists independently.
     *
     * @param array $parameters
     */
    public function setWordpressParameters($parameters)
    {
        $this->wordpress_parameters = $parameters;
    }

    /**
     * Get a specific WordPress parameter.
     *
     * IMPORTANT: this only sets the variable for the current Context!
     * Each Context exists independently.
     *
     * @param string $name
     *            Parameter name.
     *
     * @return mixed
     */
    public function getWordpressParameter($name)
    {
        return ! empty($this->wordpress_parameters[$name]) ? $this->wordpress_parameters[$name] : null;
    }

    /**
     * Get all WordPress parameters.
     *
     * @return array
     */
    public function getWordpressParameters()
    {
        return $this->wordpress_parameters;
    }

    /**
     * Get active WordPress Driver.
     *
     * @param string $name
     *            Optional. Name of specific driver to retrieve.
     *
     * @return \PaulGibbs\WordpressBehatExtension\Driver\DriverInterface
     */
    public function getDriver($name = '')
    {
        return $this->getWordpress()->getDriver($name);
    }

    /**
     * Wrap a closure in a spin check.
     *
     * This is a technique to accommodate in-progress state changes in a web page (i.e. waiting for new data to load)
     * by retrying the action for a given number of attempts, each delayed by 1 second. The closure is expected to
     * throw an exception should the expected state not (yet) exist.
     *
     * To avoid doubt, you should only need to spin when waiting for an AJAX response, after initial page load.
     *
     * @deprecated Use PaulGibbs\WordpressBehatExtension\Util\spins
     *
     * @param callable $closure
     *            Action to execute.
     * @param int $wait
     *            Optional. How long to wait before giving up, in seconds.
     */
    public function spins(callable $closure, $wait = 60)
    {
        Util\spins($closure, $wait);
    }

    /**
     * Clear Mink's browser environment.
     */
    public function resetBrowser()
    {
        $this->getSession()->reset();
    }
}
