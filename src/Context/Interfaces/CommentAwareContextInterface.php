<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Interfaces;

use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\CommentElementInterface;

/**
 *
 * @author voddenr
 *
 */
interface CommentAwareContextInterface
{
    public function createComment(array $args): array;
    public function deleteComment(int $comment_id, array $args = []);

    public function setCommentElement(CommentElementInterface $database);
}
