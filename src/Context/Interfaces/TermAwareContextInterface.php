<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Interfaces;

use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\TermElementInterface;

interface TermAwareContextInterface
{
    /**
     * Create a term in a taxonomy.
     *
     * @param string $term
     * @param string $taxonomy
     * @param array  $args     Optional. Set the values of the new term.
     *
     * @return array {
     *             @type int $id Term ID.
     *             @type string $slug Term slug.
     *         }
     */
    public function createTerm(string $term, string $taxonomy, array $args = []): array;

    /**
     * Delete a term from a taxonomy.
     *
     * @param int    $term_id
     * @param string $taxonomy
     */
    public function deleteTerm(int $term_id, string $taxonomy);

    public function setTermElement(TermElementInterface $termElement);
}
