<?php

namespace App\Import;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Core\Nation;
use App\Entity\Game\Core\PlayerAttributes;
use App\Entity\Game\Core\PlayerContract;
use App\Entity\Game\Import\Import;
use App\Import\Importer\ImporterInterface;
use DateTimeImmutable;
use Doctrine\Common\Persistence\ObjectManager;

class SaveGameImportProcessor
{
    /** @var ObjectManager */
    private $objectManager;

    /** @var CsvProcessor */
    private $csvProcessor;

    /** @var ImporterInterface[] */
    private $importers;

    public function __construct(ObjectManager $objectManager, CsvProcessor $csvProcessor, $importers)
    {
        $this->objectManager = $objectManager;
        $this->csvProcessor = $csvProcessor;
        $this->importers = $importers;
    }

    public function importSavedGameData(Career $career, string $path)
    {
        $calendar = iterator_to_array($this->csvProcessor->readLine($path.'career_calendar.csv'))[0];

        $now = new DateTimeImmutable();
        $import = new Import(
            $now,
            $career
        );
        $import->setGameDate(
            DateTimeImmutable::createFromFormat('Ymd', $calendar['currdate'])
        );

        $nationalityRepository = $this->objectManager->getRepository(Nation::class);

        $careerRow = iterator_to_array($this->csvProcessor->readLine($path.'career_users.csv'))[0];
        $career->updateFromSave(
            (int) $careerRow['userid'],
            $careerRow['firstname'],
            $careerRow['surname'],
            $nationalityRepository->findOneByGameId($careerRow['nationalityid'])
        );

        $this->objectManager->persist($career);
        $this->objectManager->persist($import);
        $this->objectManager->flush();

        $importRepository = $this->objectManager->getRepository(Import::class);
        $importId = $import->getId();

        foreach ($this->importers as $importer) {
            if (!$importer->supports($career)) {
                continue;
            }

            foreach ($importer->import($import, $path) as $i => $record) {
                $this->objectManager->persist($record);

                if (($i + 1) % 100 === 0) {
                    $this->flushAndClean($importer->cleanup());
                    $this->objectManager->refresh($import);
                    $this->objectManager->refresh($career);
                }
            }

            $this->flushAndClean($importer->cleanup());
            $this->objectManager->refresh($import);
            $this->objectManager->refresh($career);
        }
    }

    private function flushAndClean(array $importClasses): void
    {
        $this->objectManager->flush();

        foreach ($importClasses as $importClass) {
            if (in_array($importClass, [Import::class, Career::class])) {
                continue;
            }

            var_dump($importClass);
            $this->objectManager->clear($importClass);
        }
    }
}
