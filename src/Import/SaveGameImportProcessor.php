<?php

namespace App\Import;

use App\Entity\Game\Career;
use App\Entity\Game\Import\Import;
use App\Import\Importer\ImporterInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SaveGameImportProcessor
{
    /** @var ObjectManager */
    private $objectManager;

    /** @var CsvProcessor */
    private $csvProcessor;

    /** @var ImporterInterface[] */
    private $importers;

    public function __construct(ObjectManager $objectManager, CsvProcessor $csvProcessor, iterable $importers)
    {
        $this->objectManager = $objectManager;
        $this->csvProcessor = $csvProcessor;
        $this->importers = $importers;
    }

    public function importSavedGameData(Career $career, string $path)
    {
        $calendar = $this->csvProcessor->readLine($path.'career_calendar.csv');

        $now = new \DateTimeImmutable();
        $import = new Import(
            $now,
            $career
        );
        $import->setGameDate(
            \DateTimeImmutable::createFromFormat('Ymd', $calendar['currdate'])
        );

        $this->objectManager->persist($import);
        $this->objectManager->flush();

        foreach ($this->importers as $importer) {
            $importClasses = [];

            if (!$importer->supports($career)) {
                continue;
            }

            foreach ($importer->import($import, $path) as $i => $record) {
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
