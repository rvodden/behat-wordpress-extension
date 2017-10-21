<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Traits;

/**
 * Provides driver agnostic logic (helper methods) relating to comments.
 */
trait CommentAwareContextTrait
{
    use BaseAwarenessTrait;

    /**
     * Create a comment.
     *
     * @param array $args Set the values of the new comment.
     *
     * @return array {
     *             @type int $commentId Content ID.
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
     * @param int   $commentId     ID of comment to delete.
     * @param array $args   Optional. Extra parameters to pass to WordPress.
     */
    public function deleteComment($commentId, $args = [])
    {
        $this->getDriver()->comment->delete($commentId, $args);
    }
}
