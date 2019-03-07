<?php

namespace App\Import;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Import\Import;
use App\Import\Importer\ImporterInterface;
use App\Repository\Game\Core\NationRepository;
use App\Repository\Game\Import\ImportRepository;
use DateTimeImmutable;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\UnitOfWork;

class SaveGameImportProcessor
{
    /** @var ObjectManager */
    private $objectManager;

    /** @var CsvProcessor */
    private $csvProcessor;

    /** @var ImportRepository */
    private $importRepository;

    /** @var NationRepository */
    private $nationRepository;

    /** @var ImporterInterface[] */
    private $importers;

    public function __construct(
        ObjectManager $objectManager,
        ImportRepository $importRepository,
        NationRepository $nationRepository,
        CsvProcessor $csvProcessor,
        $importers
    ) {
        $this->objectManager = $objectManager;
        $this->csvProcessor = $csvProcessor;
        $this->nationRepository = $nationRepository;
        $this->importRepository = $importRepository;
        $this->importers = $importers;
    }

    public function importSavedGameData(Career $career, string $path)
    {
        $calendar = iterator_to_array($this->csvProcessor->readLine($path.'career_calendar.csv'))[0];

        $importGameDate = DateTimeImmutable::createFromFormat('Ymd', $calendar['currdate']);
        if ($career->currentSeasonOffset() > 0 && $importGameDate < $career->getCurrentSeason()->getCurrentDate()) {
            return;
        }

        $now = new DateTimeImmutable();
        $import = new Import(
            $now,
            $career
        );
        $import->setGameDate($importGameDate);

        $careerRow = iterator_to_array($this->csvProcessor->readLine($path.'career_users.csv'))[0];
        $nationality = $this->nationRepository->findOneByGame(
            $career->getGameVersion(),
            $careerRow['nationalityid']
        );
        $career->updateFromSave(
            (int) $careerRow['userid'],
            $careerRow['firstname'],
            $careerRow['surname'],
            $nationality
        );

        $this->objectManager->persist($career);
        $this->objectManager->persist($import);
        $this->objectManager->flush();

        $importId = $import->getId();

        $this->objectManager->getConnection()->getConfiguration()->setSQLLogger(null);

        foreach ($this->importers as $importer) {
            if (!$importer->supports($career)) {
                continue;
            }

            $import = $this->importRepository->find($importId);

            $importCount = 0;
            foreach ($importer->import($import, $path) as $i => $record) {
                $this->objectManager->persist($record);
                ++$importCount;

                if (($i + 1) % 100 === 0) {
                    $this->flushAndCleanup($importer, $importCount);
                }

                $import = $this->importRepository->find($importId);
            }

            $this->flushAndCleanup($importer, $importCount);
            echo "\n";
        }
    }

    private function flushAndCleanup(ImporterInterface $importer, int $importCount)
    {
        /** @var UnitOfWork $uow */
        $uow = $this->objectManager->getUnitOfWork();
        echo get_class($importer).': imported '.$importCount.' (mem: '.round(memory_get_usage(true) / 1024 / 1024, 2).' | uow: '.$uow->size().")\r";
        gc_collect_cycles();
        try {
            $this->objectManager->flush();
            $importer->cleanup($this->objectManager);
        } catch (\Throwable $e) {
            echo "\n";
            throw $e;
        }
    }
}
