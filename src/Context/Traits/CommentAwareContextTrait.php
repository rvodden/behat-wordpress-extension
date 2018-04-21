<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Context\Traits;

use PaulGibbs\WordpressBehatExtension\Driver\Element\CommentElementInterface;

/**
 * Provides driver agnostic logic (helper methods) relating to comments.
 */
trait CommentAwareContextTrait
{
    use BaseAwarenessTrait;
    
    /**
     * The the Comment Element, injected in by the CommentAwareContextInitializer
     *
     * @var CommentElementInterface $comment
     */
    private $commentElement;

    /**
     * Create a comment.
     *
     * @param array $args Set the values of the new comment.
     *
     * @return array {
     *             @type int $id Content ID.
     *         }
     */
    public function createComment(array $args): array
    {
        $comment = $this->commentElement->create($args);

        return array(
            'id' => $comment->comment_ID
        );
    }

    /**
     * Delete specified comment.
     *
     * @param int   $comment_id ID of comment to delete.
     * @param array $args       Optional. Extra parameters to pass to WordPress.
     */
    public function deleteComment(int $comment_id, array $args = [])
    {
        $this->commentElement->delete($comment_id, $args);
    }
}
