<?php
declare(strict_types = 1);
namespace PaulGibbs\WordpressBehatExtension\Context\Initialiser;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use PaulGibbs\WordpressBehatExtension\WordpressDriverManager;
use PaulGibbs\WordpressBehatExtension\Context\WordpressAwareInterface;
use PaulGibbs\WordpressBehatExtension\Context\Interfaces\CacheAwareContextInterface;
use PaulGibbs\WordpressBehatExtension\Context\Interfaces\CommentAwareContextInterface;
use PaulGibbs\WordpressBehatExtension\Context\Interfaces\ContentAwareContextInterface;
use PaulGibbs\WordpressBehatExtension\Context\Interfaces\DatabaseAwareContextInterface;
use PaulGibbs\WordpressBehatExtension\Context\Interfaces\PluginAwareContextInterface;
use PaulGibbs\WordpressBehatExtension\Context\Interfaces\TermAwareContextInterface;
use PaulGibbs\WordpressBehatExtension\Context\Interfaces\ThemeAwareContextInterface;
use PaulGibbs\WordpressBehatExtension\Context\Interfaces\UserAwareContextInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\CacheElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\CommentElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\ContentElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\DatabaseElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\PluginElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\TermElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\ThemeElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\UserElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\WidgetElementInterface;
use PaulGibbs\WordpressBehatExtension\Context\Interfaces\WidgetAwareContextInterface;

/**
 * Behat Context initialiser.
 *
 * THis is a service which symfony understands, and can inject into
 * which can subsequently initialise Behat contexts. This will be
 * unecessary when behat can deal with autowiring
 *
 * https://github.com/Behat/Symfony2Extension/issues/128
 */
class WordpressAwareInitialiser implements ContextInitializer
{

    /**
     * WordPress driver manager.
     *
     * @var WordpressDriverManager
     */
    protected $wordpress;

    /**
     * Plugin element.
     *
     * @var PluginElementInterface
     */
    protected $pluginElement;

    /**
     * Database element.
     *
     * @var DatabaseElementInterface
     */
    protected $databaseElement;

    /**
     * Cache element.
     *
     * @var CacheElementInterface
     */
    protected $cacheElement;

    /**
     * User element.
     *
     * @var UserElementInterface
     */
    protected $userElement;

    /**
     * Comment element.
     *
     * @var CommentElementInterface
     */
    protected $commentElement;

    /**
     * Content element.
     *
     * @var ContentElementInterface
     */
    protected $contentElement;

    /**
     * Term element.
     *
     * @var TermElementInterface
     */
    protected $termElement;

    /**
     * Theme element.
     *
     * @var ThemeElementInterface
     */
    protected $themeElement;

    /**
     * Widget element.
     *
     * @var WidgetElementInterface
     */
    protected $widgetElement;

    /**
     * WordPress context parameters.
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Constructor.
     *
     * @param WordpressDriverManager $wordpress
     * @param array $parameters
     * @param PluginElementInterface $pluginElement
     */
    public function __construct(
        CacheElementInterface $cacheElement,
        CommentElementInterface $commentElement,
        ContentElementInterface $contentElement,
        DatabaseElementInterface $databaseElement,
        TermElementInterface $termElement,
        ThemeElementInterface $themeElement,
        PluginElementInterface $pluginElement,
        UserElementInterface $userElement,
        WidgetElementInterface $widgetElement,
        WordpressDriverManager $wordpress,
        array $parameters
    ) {
        $this->cacheElement = $cacheElement;
        $this->commentElement = $commentElement;
        $this->contentElement = $contentElement;
        $this->databaseElement = $databaseElement;
        $this->termElement = $termElement;
        $this->themeElement = $themeElement;
        $this->pluginElement = $pluginElement;
        $this->userElement = $userElement;
        $this->widgetElement = $widgetElement;
        $this->wordpress = $wordpress;

        $this->parameters = $parameters;
    }

    /**
     * Prepare everything that a Context might need.
     *
     * It will be great to lose this class and this method and use Symfony Autowiring
     * with constructor injection for the following reasons:
     * 1) We won't need to do all this horrid type checking below
     * 2) Constructor injection of the Elements will mean that they can't get altered by accident
     * 3) The setters won't be necessary so there will be fewer methods and therefore a simpler API
     * 4) PHPStan won't get upset by all the unknown methods (I might have fixed that)
     *
     * That won't happen until this
     * https://github.com/Behat/Symfony2Extension/issues/128
     * is resolved.
     *
     * @param Context $context
     */
    public function initializeContext(Context $context)
    {
        if ($context instanceof CacheAwareContextInterface) {
            $this->initializeCacheAwareContext($context);
        }

        if ($context instanceof CommentAwareContextInterface) {
            $this->initializeCommentAwareContext($context);
        }

        if ($context instanceof ContentAwareContextInterface) {
            $this->initializeContentAwareContext($context);
        }

        if ($context instanceof DatabaseAwareContextInterface) {
            $this->initializeDatabaseAwareContext($context);
        }

        if ($context instanceof PluginAwareContextInterface) {
            $this->initializePluginAwareContext($context);
        }

        if ($context instanceof TermAwareContextInterface) {
            $this->initializeTermAwareContext($context);
        }

        if ($context instanceof ThemeAwareContextInterface) {
            $this->initializeThemeAwareContext($context);
        }

        if ($context instanceof UserAwareContextInterface) {
            $this->initializeUserAwareContext($context);
        }

        if ($context instanceof WidgetAwareContextInterface) {
            $this->initializeWidgetAwareContext($context);
        }

        if ($context instanceof WordpressAwareInterface) {
            $this->initializeWordpressAwareContext($context);
        }
    }

    protected function initializeCacheAwareContext(CacheAwareContextInterface $context)
    {
        $context->setCacheElement($this->cacheElement);
    }

    protected function initializeCommentAwareContext(CommentAwareContextInterface $context)
    {
        $context->setCommentElement($this->commentElement);
    }

    protected function initializeContentAwareContext(ContentAwareContextInterface $context)
    {
        $context->setContentElement($this->contentElement);
    }

    protected function initializeDatabaseAwareContext(DatabaseAwareContextInterface $context)
    {
        $context->setDatabaseElement($this->databaseElement);
    }

    protected function initializePluginAwareContext(PluginAwareContextInterface $context)
    {
        $context->setPluginElement($this->pluginElement);
    }

    protected function initializeTermAwareContext(TermAwareContextInterface $context)
    {
        $context->setTermElement($this->termElement);
    }

    protected function initializeThemeAwareContext(ThemeAwareContextInterface $context)
    {
        $context->setThemeElement($this->themeElement);
    }

    protected function initializeUserAwareContext(UserAwareContextInterface $context)
    {
        $context->setUserElement($this->userElement);
    }

    protected function initializeWidgetAwareContext(WidgetAwareContextInterface $context)
    {
        $context->setWidgetElement($this->widgetElement);
    }

    protected function initializeWordpressAwareContext(WordpressAwareInterface $context)
    {
        $context->setWordpress($this->wordpress);
        $context->setWordpressParameters($this->parameters);
    }
}
