<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Career\Season\SeasonTeamLeague;
use App\Entity\Game\Core\League;
use App\Entity\Game\Core\Team;
use App\Entity\Game\Import\Import;
use App\Import\CsvProcessor;
use App\Repository\Game\Career\Season\SeasonTeamLeagueRepository;
use App\Repository\Game\Core\LeagueRepository;
use App\Repository\Game\Core\TeamRepository;
use Doctrine\Common\Persistence\ObjectManager;

class SeasonTeamLeagueImporter extends AbstractCsvImporter
{
    protected static $csvFile = 'leagueteamlinks.csv';

    /** @var SeasonTeamLeagueRepository */
    private $seasonTeamLeagueRepository;
    /** @var TeamRepository */
    private $teamRepository;
    /** @var LeagueRepository */
    private $leagueRepository;

    public function __construct(
        SeasonTeamLeagueRepository $seasonTeamLeagueRepository,
        TeamRepository $teamRepository,
        LeagueRepository $leagueRepository,
        CsvProcessor $csvProcessor
    ) {
        parent::__construct($csvProcessor);

        $this->seasonTeamLeagueRepository = $seasonTeamLeagueRepository;
        $this->teamRepository = $teamRepository;
        $this->leagueRepository = $leagueRepository;
    }

    public function supports(Career $career): bool
    {
        return $career->getGameVersion()->getYear() <= 18;
    }

    public function cleanup(ObjectManager $objectManager): void
    {
        $objectManager->clear(SeasonTeamLeague::class);
        $objectManager->clear(League::class);
        $objectManager->clear(Team::class);
    }

    protected function processRow(Import $import, array $row): ?object
    {
        $season = $import->getCareer()->getCurrentSeason();

        $team = $this->teamRepository->findOneByGame(
            $import->getCareer()->getGameVersion(),
            (int) $row['teamid']
        );

        $league = $this->leagueRepository->findOneByGame(
            $import->getCareer()->getGameVersion(),
            (int) $row['leagueid']
        );

        $seasonTeamLeague = $this->seasonTeamLeagueRepository->findOneBy([
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

        return $seasonTeamLeague;
    }
}
