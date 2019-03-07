<?php

namespace App\Import\Importer;

use App\Entity\Game\Import\Import;
use App\Import\CsvProcessor;
use Doctrine\Common\Persistence\ObjectManager;

abstract class AbstractCsvImporter implements ImporterInterface
{
    protected static $csvFile;

    /** @var CsvProcessor */
    private $csvProcessor;

    public function __construct(CsvProcessor $csvProcessor, ?string $csvFile = null)
    {
        $this->csvProcessor = $csvProcessor;

        if ($csvFile) {
            static::$csvFile = $csvFile;
        }
    }

    public function import(Import $import, string $path)
    {
        foreach ($this->csvProcessor->readLine($path.'/'.static::$csvFile) as $row) {
            if ($result = $this->processRow($import, $row)) {
                yield $result;
            }
        }
    }

    public function cleanup(ObjectManager $objectManager): void
    {
    }

    /**
     * Process a row as an associative array of headerName => value from the imported CSV file, Returning the entity
     * to be persisted.
     *
     * @param Import $import Import entity
     * @param array  $row    Array of headerName => value key/value pairs
     *
     * @return object Entity to save
     */
    abstract protected function processRow(Import $import, array $row): ?object;
}
