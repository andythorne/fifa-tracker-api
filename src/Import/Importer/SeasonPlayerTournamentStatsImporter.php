<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Career\CareerPlayer;
use App\Entity\Game\Career\Season\SeasonPlayerTournamentStats;
use App\Entity\Game\Import\Import;
use App\Import\CsvProcessor;
use Doctrine\Common\Persistence\ObjectManager;

class SeasonPlayerTournamentStatsImporter implements ImporterInterface
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
        $file = $path.'career_compdata_playerstats.csv';

        $seasonPlayerTournamentStatsRepository = $this->objectManager->getRepository(SeasonPlayerTournamentStats::class);
        $careerPlayerRepository = $this->objectManager->getRepository(CareerPlayer::class);

        $season = $import->getCareer()->getCurrentSeason();

        $playerGroupedData = [];
        foreach ($this->csvProcessor->readLine($file) as $row) {
            $playerId = $row['playerid'];
            $playerGroupedData[$playerId]['unk1'] += $row['unk1'];
            $playerGroupedData[$playerId]['avg'] += $row['avg'];
            $playerGroupedData[$playerId]['app'] += $row['app'];
            $playerGroupedData[$playerId]['goals'] += $row['goals'];
            $playerGroupedData[$playerId]['assists'] += $row['assists'];
            $playerGroupedData[$playerId]['yellowcards'] += $row['yellowcards'];
            $playerGroupedData[$playerId]['redcards'] += $row['redcards'];
            $playerGroupedData[$playerId]['cleansheets'] += $row['cleansheets'];
        }

        foreach ($playerGroupedData as $playerId => $playerData) {
            $careerPlayer = $careerPlayerRepository->findOneBy([
                'player.gameId' => (int) $playerId,
                'gameVersion' => $import->getCareer()->getGameVersion(),
            ]);

            $seasonPlayerTournamentStats = $seasonPlayerTournamentStatsRepository->findOneBy([
                'season' => $season,
                'careerPlayer' => $careerPlayer,
            ]);

            if (!$seasonPlayerTournamentStats instanceof SeasonPlayerTournamentStats) {
                $seasonPlayerTournamentStats = new SeasonPlayerTournamentStats($season, $careerPlayer);
            }

            $seasonPlayerTournamentStats->setMinutesPlayed((int) $playerData['unk1']);
            $seasonPlayerTournamentStats->setAvgRating((int) $playerData['avg']);
            $seasonPlayerTournamentStats->setApps((int) $playerData['app']);
            $seasonPlayerTournamentStats->setGoals((int) $playerData['goals']);
            $seasonPlayerTournamentStats->setAssists((int) $playerData['assists']);
            $seasonPlayerTournamentStats->setYellowCards((int) $playerData['yellowcards']);
            $seasonPlayerTournamentStats->setRedCards((int) $playerData['redcards']);
            $seasonPlayerTournamentStats->setCleanSheets((int) $playerData['cleansheets']);

            yield $seasonPlayerTournamentStats;
        }
    }

    public function supports(Career $career): bool
    {
        return $career->getGameVersion()->getYear() <= 18;
    }

    public function cleanup(): array
    {
        return [
            SeasonPlayerTournamentStats::class,
            CareerPlayer::class,
        ];
    }
}
