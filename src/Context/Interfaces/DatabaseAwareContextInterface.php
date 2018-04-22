<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Interfaces;

use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\DatabaseElementInterface;

interface DatabaseAwareContextInterface
{
    /**
     * Export WordPress database.
     *
     * @param array $args
     *
     * @return string Path to the export file.
     */
    public function exportDatabase(array $args): string;

    /**
     * Import WordPress database.
     *
     * @param array $args
     */
    public function importDatabase(array $args);

    public function setDatabaseElement(DatabaseElementInterface $databaseElement);
}
