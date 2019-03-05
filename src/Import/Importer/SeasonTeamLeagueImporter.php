<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Career\Season\SeasonTeamLeague;
use App\Entity\Game\Core\League;
use App\Entity\Game\Core\Team;
use App\Entity\Game\Import\Import;
use App\Import\CsvProcessor;
use Doctrine\Common\Persistence\ObjectManager;

class SeasonTeamLeagueImporter implements ImporterInterface
{
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
        $file = $path.'leagueteamlinks.csv';

        $seasonTeamLeagueRepository = $this->objectManager->getRepository(SeasonTeamLeague::class);
        $teamRepository = $this->objectManager->getRepository(Team::class);
        $leagueRepository = $this->objectManager->getRepository(League::class);

        $season = $import->getCareer()->getCurrentSeason();

        foreach ($this->csvProcessor->readLine($file) as $row) {
            $team = $teamRepository->findOneBy([
                'gameId' => (int) $row['teamid'],
                'gameVersion' => $import->getCareer()->getGameVersion(),
            ]);

            $league = $leagueRepository->findOneBy([
                'gameId' => (int) $row['leagueid'],
                'gameVersion' => $import->getCareer()->getGameVersion(),
            ]);

            $seasonTeamLeague = $seasonTeamLeagueRepository->findOneBy([
                'season' => $season,
                'team' => $team,
                'league' => $league,
            ]);

            if (!$seasonTeamLeague instanceof SeasonTeamLeague) {
                $seasonTeamLeague = new SeasonTeamLeague($import, $season, $team, $league);
            }

            // TODO: all these stats are wrong.
            $seasonTeamLeague->setHomeWon((int) $row['homewins']);
            $seasonTeamLeague->setHomeDrawn((int) $row['homedraws']);
            $seasonTeamLeague->setHomeLost((int) $row['homelosses']);
            $seasonTeamLeague->setHomeGoalsFor((int) $row['homegf']);
            $seasonTeamLeague->setHomeGoalsAgainst((int) $row['homega']);

            $seasonTeamLeague->setAwayWon((int) $row['awaywins']);
            $seasonTeamLeague->setAwayDrawn((int) $row['awaydraws']);
            $seasonTeamLeague->setAwayLost((int) $row['awaylosses']);
            $seasonTeamLeague->setAwayGoalsFor((int) $row['awaygf']);
            $seasonTeamLeague->setAwayGoalsAgainst((int) $row['awayga']);

            $seasonTeamLeague->setPosition((int) $row['currenttableposition']);

            yield $seasonTeamLeague;
        }
    }

    public function supports(Career $career): bool
    {
        return $career->getGameVersion()->getYear() <= 18;
    }

    public function cleanup(): array
    {
        return [
            SeasonTeamLeague::class,
            League::class,
            Team::class,
        ];
    }
}
