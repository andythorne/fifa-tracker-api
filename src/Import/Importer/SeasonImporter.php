<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Career\Season\Season;
use App\Entity\Game\Import\Import;
use App\Import\CsvProcessor;
use App\Repository\Game\Career\Season\SeasonRepository;
use DateTimeImmutable;

class SeasonImporter extends AbstractCsvImporter
{
    protected static $csvFile = 'career_calendar.csv';

    /** @var SeasonRepository */
    private $seasonRepository;

    public function __construct(SeasonRepository $seasonRepository, CsvProcessor $csvProcessor)
    {
        parent::__construct($csvProcessor);

        $this->seasonRepository = $seasonRepository;
    }

    public function supports(Career $career): bool
    {
        return $career->getGameVersion()->getYear() <= 18;
    }

    protected function processRow(Import $import, array $row): ?object
    {
        $career = $import->getCareer();
        $year = (int) DateTimeImmutable::createFromFormat('Ymd', $row['startdate'])->format('Y');
        $season = $this->seasonRepository->findOneBy([
            'career' => $career,
            'year' => $year,
        ]);

        $currentDate = \DateTimeImmutable::createFromFormat('Ymd', $row['currdate']);
        if ($season instanceof Season) {
            if ($currentDate < $season->getCurrentDate()) {
                throw new \Exception('Cannot update the past!');
            }

            return null;
        }

        $season = new Season($career, $year);
        $season->setCurrentDate($currentDate);

        return $season;
    }
}
