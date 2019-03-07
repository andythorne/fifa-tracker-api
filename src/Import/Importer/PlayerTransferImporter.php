<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Career\CareerPlayer;
use App\Entity\Game\Career\PlayerTransfer;
use App\Entity\Game\Core\Team;
use App\Entity\Game\Import\Import;
use App\Import\CsvProcessor;
use App\Repository\Game\Career\CareerPlayerRepository;
use App\Repository\Game\Career\PlayerTransferRepository;
use App\Repository\Game\Core\TeamRepository;
use DateTimeImmutable;
use Doctrine\Common\Persistence\ObjectManager;

class PlayerTransferImporter extends AbstractCsvImporter
{
    protected static $csvFile = 'career_transferoffer.csv';

    /** @var CareerPlayerRepository */
    private $careerPlayerRepository;

    /** @var PlayerTransferRepository */
    private $playerTransferRepository;

    /** @var TeamRepository */
    private $teamRepository;

    public function __construct(
        CareerPlayerRepository $careerPlayerRepository,
        PlayerTransferRepository $playerTransferRepository,
        TeamRepository $teamRepository,
        CsvProcessor $csvProcessor
    ) {
        parent::__construct($csvProcessor);

        $this->careerPlayerRepository = $careerPlayerRepository;
        $this->playerTransferRepository = $playerTransferRepository;
        $this->teamRepository = $teamRepository;
    }

    public function supports(Career $career): bool
    {
        return $career->getGameVersion()->getYear() <= 18;
    }

    public function cleanup(ObjectManager $objectManager): void
    {
        $objectManager->clear(PlayerTransfer::class);
        $objectManager->clear(CareerPlayer::class);
        $objectManager->clear(Team::class);
    }

    protected function processRow(Import $import, array $row): ?object
    {
        if ((int) $row['stage'] !== 2) {
            return null;
        }

        // TODO: this is wrong!
        $playerTransfer = $this->playerTransferRepository->findOneBy([
            'gameId' => $row['offerid'],
        ]);

        if ($playerTransfer instanceof PlayerTransfer) {
            return null;
        }

        $careerPlayer = $this->careerPlayerRepository->findOneForCareerAndPlayerId($import->getCareer(), (int) $row['playerid']);

        $teamFrom = $this->teamRepository->findOneByGame(
            $import->getCareer()->getGameVersion(),
            (int) $row['offerteamid']
        );

        $teamTo = $this->teamRepository->findOneByGame(
            $import->getCareer()->getGameVersion(),
            (int) $row['teamid']
        );

        $exchangePlayer = null;
        $exchangePlayerId = (int) $row['exchangeplayerid'];
        if ($exchangePlayerId > 0) {
            $careerPlayer = $this->careerPlayerRepository->findOneForCareerAndPlayerId($import->getCareer(), (int) $row['playerid']);
        }

        $signedAt = DateTimeImmutable::createFromFormat('Ymd+', $row['date']);
        $joinedAt = DateTimeImmutable::createFromFormat('Ymd+', $row['startdate']) ?: null;

        return new PlayerTransfer(
            $import,
            (int) $row['offerid'],
            $careerPlayer,
            $teamFrom,
            $teamTo,
            $exchangePlayer,
            (int) $row['offeredfee'],
            $signedAt,
            $joinedAt,
            $row['precontract'] === '1'
        );
    }
}
