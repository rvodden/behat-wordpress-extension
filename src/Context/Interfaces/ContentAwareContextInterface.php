<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Interfaces;

use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\ContentElementInterface;

interface ContentAwareContextInterface
{
    /**
     * Create content.
     *
     * @param array $args Set the values of the new content item.
     *
     * @return array {
     *             @type int    $id   Content ID.
     *             @type string $slug Content slug.
     *             @type string $url  Content permalink.
     *         }
     */
    public function createContent(array $args): array;

    /**
     * Get content from its title.
     *
     * @param string $title     The title of the content to get.
     * @param string $post_type Post type(s) to consider when searching for the content.
     *
     * @return array {
     *             @type int    $id   Content ID.
     *             @type string $slug Content slug.
     *             @type string $url  Content url.
     *         }
     */
    public function getContentFromTitle(string $title, string $post_type = ''): array;

    /**
     * Delete specified content.
     *
     * @param int   $content_id ID of content to delete.
     * @param array $args       Optional. Extra parameters to pass to WordPress.
     */
    public function deleteContent(int $content_id, array $args = []);

    /**
     * Set the contentElement
     */
    public function setContentElement(ContentElementInterface $contentElement);
}
