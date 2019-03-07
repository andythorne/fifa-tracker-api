<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Core\League;
use App\Entity\Game\Import\Import;
use App\Import\CsvProcessor;
use App\Repository\Game\Core\LeagueRepository;
use App\Repository\Game\Core\NationRepository;
use Doctrine\Common\Persistence\ObjectManager;

class LeagueImporter extends AbstractCsvImporter
{
    protected static $csvFile = 'leagues.csv';

    /** @var LeagueRepository */
    private $leagueRepository;

    /** @var NationRepository */
    private $nationRepository;

    public function __construct(LeagueRepository $leagueRepository, NationRepository $nationRepository, CsvProcessor $csvProcessor)
    {
        parent::__construct($csvProcessor);

        $this->leagueRepository = $leagueRepository;
        $this->nationRepository = $nationRepository;
    }

    public function supports(Career $career): bool
    {
        return $career->getGameVersion()->getYear() <= 18;
    }

    public function cleanup(ObjectManager $objectManager): void
    {
        $objectManager->clear(League::class);
    }

    protected function processRow(Import $import, array $row): ?object
    {
        $id = (int) $row['leagueid'];

        /** @var League $currentRecord */
        $currentRecord = $this->leagueRepository->findOneByGame(
            $import->getCareer()->getGameVersion(),
            $id
        );

        if ($currentRecord instanceof League) {
            return null;
        }

        $nation = $this->nationRepository->findOneByGame(
            $import->getCareer()->getGameVersion(),
            (int) $row['countryid']
        );

        return new League(
            $import->getCareer()->getGameVersion(),
            $id,
            $row['leaguename'],
            $row['level'],
            $nation
        );
    }
}
