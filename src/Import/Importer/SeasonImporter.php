<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Career\Season\Season;
use App\Entity\Game\Import\Import;
use App\Import\CsvProcessor;
use DateTimeImmutable;
use Doctrine\Common\Persistence\ObjectManager;

class SeasonImporter implements ImporterInterface
{
    public const ROW_MAP = [
    ];

    /** @var ObjectManager */
    private $objectManager;

    /** @var CsvProcessor */
    private $csvProcessor;

    public function __construct(ObjectManager $objectManager, CsvProcessor $csvProcessor)
    {
        $this->objectManager = $objectManager;
        $this->csvProcessor = $csvProcessor;
    }

    public function import(Import $import, string $path)
    {
        $file = $path.'career_calendar.csv';

        $seasonRepository = $this->objectManager->getRepository(Season::class);
        $career = $import->getCareer();

        foreach ($this->csvProcessor->readLine($file) as $row) {
            $year = (int) DateTimeImmutable::createFromFormat('Ymd', $row['startdate'])->format('Y');
            $season = $seasonRepository->findOneBy([
                'career' => $career,
                'year' => $year,
            ]);

            $currentDate = \DateTimeImmutable::createFromFormat('Ymd', $row['currdate']);
            if ($season instanceof Season) {
                if ($currentDate < $season->getCurrentDate()) {
                    throw new \Exception('Cannot update the past!');
                }

                continue;
            }

            $season = new Season($career, $year);
            $season->setCurrentDate($currentDate);

            yield $season;
        }
    }

    public function supports(Career $career): bool
    {
        return $career->getGameVersion()->getYear() <= 18;
    }

    public function cleanup(): array
    {
        return [
            Season::class,
        ];
    }
}
