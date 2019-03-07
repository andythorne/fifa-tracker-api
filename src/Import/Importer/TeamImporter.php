<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Core\Team;
use App\Entity\Game\Import\Import;
use App\Import\CsvProcessor;
use App\Repository\Game\Core\TeamRepository;
use Doctrine\Common\Persistence\ObjectManager;

class TeamImporter extends AbstractCsvImporter
{
    protected static $csvFile = 'teams.csv';
    /** @var TeamRepository */
    private $teamRepository;

    public function __construct(
        TeamRepository $teamRepository,
        CsvProcessor $csvProcessor
    ) {
        parent::__construct($csvProcessor);

        $this->teamRepository = $teamRepository;
    }

    public function supports(Career $career): bool
    {
        return $career->getGameVersion()->getYear() <= 18;
    }

    public function cleanup(ObjectManager $objectManager): void
    {
        $objectManager->clear(Team::class);
    }

    protected function processRow(Import $import, array $row): ?object
    {
        $teamId = (int) $row['teamid'];

        /** @var Team $currentRecord */
        $currentRecord = $this->teamRepository->findOneByGame($import->getCareer()->getGameVersion(), $teamId);

        if ($currentRecord instanceof Team) {
            return null;
        }

        return new Team(
            $import->getCareer()->getGameVersion(),
            $teamId,
            $row['teamname'],
            $row['foundationyear'],
            sprintf('#%02x%02x%02x', (int) $row['teamcolor1r'], (int) $row['teamcolor1g'], (int) $row['teamcolor1b']),
            sprintf('#%02x%02x%02x', (int) $row['teamcolor2r'], (int) $row['teamcolor2g'], (int) $row['teamcolor2b']),
            sprintf('#%02x%02x%02x', (int) $row['teamcolor3r'], (int) $row['teamcolor3g'], (int) $row['teamcolor3b'])
        );
    }
}
