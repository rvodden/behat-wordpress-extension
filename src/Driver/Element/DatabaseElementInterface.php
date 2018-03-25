<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Driver\Element;

interface DatabaseElementInterface extends ElementInterface
{
    /**
     * Export site database.
     *
     * @param int   $id   Not used.
     * @param array $args
     *
     * @return string Path to the database dump.
     */
    public function export($id, $args = []) : string;
    
    /**
     * Import site database.
     *
     * @param int   $id   Not used.
     * @param array $args
     */
    public function import($id, $args = []);
}
