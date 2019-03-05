<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Career\CareerPlayer;
use App\Entity\Game\Career\PlayerTransfer;
use App\Entity\Game\Core\Team;
use App\Entity\Game\Import\Import;
use App\Import\CsvProcessor;
use DateTimeImmutable;
use Doctrine\Common\Persistence\ObjectManager;

class PlayerTransferImporter implements ImporterInterface
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
        $file = $path.'career_transferoffer.csv';

        $playerTransferRepository = $this->objectManager->getRepository(PlayerTransfer::class);
        $careerPlayerRepository = $this->objectManager->getRepository(CareerPlayer::class);
        $teamRepository = $this->objectManager->getRepository(Team::class);

        foreach ($this->csvProcessor->readLine($file) as $row) {
            if ((int) $row['stage'] !== 2) {
                continue;
            }

            $playerTransfer = $playerTransferRepository->findOneBy([
                'gameId' => $row['offerid'],
            ]);

            if ($playerTransfer instanceof PlayerTransfer) {
                continue;
            }

            $careerPlayer = $careerPlayerRepository->findOneBy([
                'player.gameId' => (int) $row['playerid'],
                'career' => $import->getCareer(),
            ]);

            $teamFrom = $teamRepository->findOneBy([
                'gameId' => (int) $row['offerteamid'],
                'gameVersion' => $import->getCareer()->getGameVersion(),
            ]);

            $teamTo = $teamRepository->findOneBy([
                'gameId' => (int) $row['teamid'],
                'gameVersion' => $import->getCareer()->getGameVersion(),
            ]);

            $exchangePlayer = null;
            $exchangePlayerId = (int) $row['exchangeplayerid'];
            if ($exchangePlayerId > 0) {
                $exchangePlayer = $careerPlayerRepository->findOneBy([
                    'player.gameId' => $exchangePlayerId,
                    'career' => $import->getCareer(),
                ]);
            }

            yield new PlayerTransfer(
                $import,
                (int) $row['offerid'],
                $careerPlayer,
                $teamFrom,
                $teamTo,
                $exchangePlayer,
                (int) $row['offeredfee'],
                DateTimeImmutable::createFromFormat('Ymd*', $row['date']),
                DateTimeImmutable::createFromFormat('Ymd*', $row['startdate']),
                $row['precontract'] === '1'
            );
        }
    }

    public function supports(Career $career): bool
    {
        return $career->getGameVersion()->getYear() <= 18;
    }

    public function cleanup(): array
    {
        return [
            PlayerTransfer::class,
            CareerPlayer::class,
            Team::class,
        ];
    }
}
