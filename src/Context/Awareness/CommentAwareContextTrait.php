<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Awareness;

/**
 * Provides driver agnostic logic (helper methods) relating to comments.
 */
trait CommentAwareContextTrait
{
    use BaseAwarenesTrait;

    /**
     * Create a comment.
     *
     * @param array $args Set the values of the new comment.
     *
     * @return array {
     *         @type int $id Content ID.
     *         }
     */
    public function createComment($args)
    {
        $comment = $this->getDriver()->comment->create($args);

        return array(
            'id' => $comment->comment_ID
        );
    }

    /**
     * Delete specified comment.
     *
     * @param int   $id     ID of comment to delete.
     * @param array $args   Optional. Extra parameters to pass to WordPress.
     */
    public function deleteComment($id, $args = [])
    {
        $this->getDriver()->comment->delete($id, $args);
    }
}
