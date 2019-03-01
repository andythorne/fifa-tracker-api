<?php

namespace App\Import;

use App\Entity\Game\Career\Career;
use App\Import\Importer\ImporterInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SaveGameImportProcessor
{
    /** @var ObjectManager */
    private $objectManager;

    /** @var ImporterInterface[] */
    private $importers;

    public function __construct(ObjectManager $objectManager, iterable $importers)
    {
        $this->objectManager = $objectManager;
        $this->importers = $importers;
    }

    public function importSavedGameData(Career $career, string $path)
    {
        foreach ($this->importers as $importer) {
            $importClasses = [];

            foreach ($importer->import($career, $path) as $i => $record) {
                $this->objectManager->persist($record);

                $importClasses[] = get_class($record);

                if ($i % 100 === 0) {
                    $this->flushAndClean($importClasses);
                    $importClasses = [];
                }
            }

            $this->flushAndClean($importClasses);
        }
    }

    private function flushAndClean(array $importClasses): void
    {
        $this->objectManager->flush();
        foreach ($importClasses as $importClass) {
            if ($importClass === Career::class) {
                continue;
            }
            $this->objectManager->clear($importClass);
        }
    }
}
