<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Awareness;

trait DatabaseAwareContextTrait
{
    /**
     * Start a database transaction.
     */
    public function startTransaction()
    {
        $this->getDriver()->database->startTransaction();
    }

    /**
     * End (rollback) a database transaction.
     */
    public function endTransaction()
    {
        $this->getDriver()->database->endTransaction();
    }

    /**
     * Export WordPress database.
     *
     * @param array $args
     *
     * @return string Path to the export file.
     */
    public function exportDatabase($args)
    {
        return $this->getDriver()->database->export(0, $args);
    }

    /**
     * Import WordPress database.
     *
     * @param array $args
     */
    public function importDatabase($args)
    {
        $this->getDriver()->database->import(0, $args);
    }
}
