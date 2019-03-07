<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Career\CareerPlayer;
use App\Entity\Game\Core\Team;
use App\Entity\Game\Import\Import;
use App\Import\CsvProcessor;
use App\Repository\Game\Career\CareerPlayerRepository;
use App\Repository\Game\Core\TeamRepository;
use Doctrine\Common\Persistence\ObjectManager;

class PlayerTeamImporter extends AbstractCsvImporter
{
    protected static $csvFile = 'teamplayerlinks.csv';

    /** @var CareerPlayerRepository */
    private $careerPlayerRepository;

    /** @var TeamRepository */
    private $teamRepository;

    public function __construct(
        CareerPlayerRepository $careerPlayerRepository,
        TeamRepository $teamRepository,
        CsvProcessor $csvProcessor
    ) {
        parent::__construct($csvProcessor);

        $this->careerPlayerRepository = $careerPlayerRepository;
        $this->teamRepository = $teamRepository;
    }

    public function supports(Career $career): bool
    {
        return $career->getGameVersion()->getYear() <= 18;
    }

    public function cleanup(ObjectManager $objectManager): void
    {
        $objectManager->clear(CareerPlayer::class);
        $objectManager->clear(Team::class);
    }

    protected function processRow(Import $import, array $row): ?object
    {
        $careerPlayer = $this->careerPlayerRepository->findOneForCareerAndPlayerId(
            $import->getCareer(),
            (int) $row['playerid']
        );

        if (!$careerPlayer instanceof CareerPlayer) {
            throw new \RuntimeException('CareerPlayer not imported yet.');
        }

        $team = $this->teamRepository->findOneByGame(
            $import->getCareer()->getGameVersion(),
            (int) $row['teamid']
        );

        $careerPlayer->setTeam($team);

        return $careerPlayer;
    }
}
