<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Awareness;

/**
 * Provides driver agnostic logic (helper methods) relating to posts and content.
 */
trait ContentAwareContextTrait
{
    use BaseAwarenessTrait;

    /**
     * Create content.
     *
     * @param array $args Set the values of the new content item.
     *
     * @return array {
     *             @type int $id Content ID.
     *             @type string $slug Content slug.
     *             @type string $url Content permalink.
     *         }
     */
    public function createContent($args)
    {
        $content = $this->getDriver()->content->create($args);

        return array(
            'id'   => $content->ID,
            'slug' => $content->post_name,
            'url'  => $content->url
        );
    }

    public function getContentFromTitle($title, $post_type = '')
    {
        return $this->getDriver()->getContentFromTitle($title, $post_type);
    }

    /**
     * Delete specified content.
     *
     * @param int   $id    ID of content to delete.
     * @param array $args  Optional. Extra parameters to pass to WordPress.
     */
    public function deleteContent($id, $args = [])
    {
        $this->getDriver()->content->delete($id, $args);
    }
}
