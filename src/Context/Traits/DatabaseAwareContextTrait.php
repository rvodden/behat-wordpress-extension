<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Context\Traits;

use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\DatabaseElementInterface;

/**
 * Provides driver agnostic logic (helper methods) relating to the database.
 */
trait DatabaseAwareContextTrait
{
    use BaseAwarenessTrait;

    /**
     * @var DatabaseElementInterface $database
     */
    var $databaseElement;

    public function exportDatabase(array $args): string
    {
        return $this->databaseElement->export(0, $args);
    }

    public function importDatabase(array $args)
    {
        $this->databaseElement->import(0, $args);
    }

    public function setDatabaseElement(DatabaseElementInterface $databaseElement) {
        $this->databaseElement = $databaseElement;
    }
}
