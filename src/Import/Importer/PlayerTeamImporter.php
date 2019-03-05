<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Career\CareerPlayer;
use App\Entity\Game\Core\Team;
use App\Entity\Game\Import\Import;
use App\Import\CsvProcessor;
use Doctrine\Common\Persistence\ObjectManager;

class PlayerTeamImporter implements ImporterInterface
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
        $file = $path.'teamplayerlinks.csv';

        $careerPlayerRepository = $this->objectManager->getRepository(CareerPlayer::class);
        $teamRepository = $this->objectManager->getRepository(Team::class);

        foreach ($this->csvProcessor->readLine($file) as $row) {
            $careerPlayer = $careerPlayerRepository->findOneBy([
                'career' => $import->getCareer(),
                'player.gameId' => (int) $row['playerid'],
            ]);

            $team = $teamRepository->findOneBy([
                'gameId' => (int) $row['teamid'],
                'gameVersion' => $import->getCareer()->getGameVersion(),
            ]);

            $careerPlayer->setTeam($team);

            yield $careerPlayer;
        }
    }

    public function supports(Career $career): bool
    {
        return $career->getGameVersion()->getYear() <= 18;
    }

    public function cleanup(): array
    {
        return [
            CareerPlayer::class,
            Team::class,
        ];
    }
}
