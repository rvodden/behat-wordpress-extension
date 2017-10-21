<?php
namespace PaulGibbs\WordpressBehatExtension\Driver;

use RuntimeException;

/**
 * Connect Behat to WordPress by loading WordPress directly into the global scope.
 */
class WpphpDriver extends BaseDriver
{
    /**
     * Path to WordPress' files.
     *
     * @var string
     */
    protected $path = '';

    /**
     * WordPres database object.
     *
     * @var \wpdb
     */
    protected $wpdb;

    /**
     * Constructor.
     *
     * @param string $path Path to WordPress site's files.
     */
    public function __construct($path)
    {
        $this->path = realpath($path);
    }

    /**
     * Set up anything required for the driver.
     *
     * Called when the driver is used for the first time.
     *
     * @throws \RuntimeException
     */
    public function bootstrap()
    {
        if (! defined('ABSPATH')) {
            define('ABSPATH', "{$this->path}/");
        }

        $_SERVER['DOCUMENT_ROOT']   = $this->path;
        $_SERVER['HTTP_HOST']       = '';
        $_SERVER['REQUEST_METHOD']  = 'GET';
        $_SERVER['REQUEST_URI']     = '/';
        $_SERVER['SERVER_NAME']     = '';
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';

        if (! file_exists("{$this->path}/index.php")) {
            throw new RuntimeException(sprintf('WordPress PHP driver cannot find WordPress at %s.', $this->path));
        }

        // "Cry 'Havoc!' and let slip the dogs of war".
        require_once "{$this->path}/wp-blog-header.php";

        if (! function_exists('activate_plugin')) {
            require_once "{$this->path}/wp-admin/includes/plugin.php";
            require_once "{$this->path}/wp-admin/includes/plugin-install.php";
        }

        $this->wpdb            = $GLOBALS['wpdb'];
        $this->is_bootstrapped = true;
    }


    /*
     * Internal helpers.
     */

    /**
     * Get information about a plugin.
     *
     * @param string $name
     *
     * @return string Plugin filename and path.
     */
    public function getPlugin($name)
    {
        foreach (array_keys(get_plugins()) as $file) {
            // Logic taken from WP-CLI.
            if ($file === "{$name}.php" || ($name && $file === $name) || (dirname($file) === $name && $name !== '.')) {
                return $file;
            }
        }

        return '';
    }


    /*
     * Backwards compatibility.
     */
    /**
     * Create content.
     *
     * @param array $args Set the values of the new content item.
     *
     * @return array {
     *     @type int    $id   Content ID.
     *     @type string $slug Content slug.
     *     @type string $url  Content permalink.
     * }
     */
    public function createContent($args)
    {
        $post = $this->content->create($args);

        return array(
            'id'   => (int) $post->ID,
            'slug' => $post->post_name,
            'url'  => get_permalink($post),
        );
    }

    /**
     * Delete specified content.
     *
     * @param int   $id   ID of content to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteContent($id, $args = [])
    {
        $this->content->delete($id, $args);
    }

    /**
     * Get content from its title.
     *
     * @param string $title     The title of the content to get.
     * @param string $post_type Post type(s) to consider when searching for the content.
     *
     * @throws \UnexpectedValueException
     *
     * @return array {
     *     @type int    $id   Content ID.
     *     @type string $slug Content slug.
     *     @type string $url  Content url.
     * }
     */
    public function getContentFromTitle($title, $post_type = '')
    {
        $post = $this->content->get($title, ['by' => 'title', 'post_type' => $post_type]);

        return array(
            'id'   => $post->ID,
            'slug' => $post->post_name,
            'url'  => get_permalink($post),
        );
    }
}
