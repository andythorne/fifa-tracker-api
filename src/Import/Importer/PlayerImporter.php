<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Career\CareerPlayer;
use App\Entity\Game\Career\CareerPlayerAttributes;
use App\Entity\Game\Core\Nation;
use App\Entity\Game\Core\Player;
use App\Entity\Game\Core\PlayerContract;
use App\Entity\Game\Core\PlayerName;
use App\Entity\Game\Import\Import;
use App\Import\CsvProcessor;
use App\Import\Utils\FifaDateTransformer;
use Doctrine\Common\Persistence\ObjectManager;

class PlayerImporter implements ImporterInterface
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
        $file = $path.'players.csv';

        $playerRepository = $this->objectManager->getRepository(Player::class);
        $careerPlayerRepository = $this->objectManager->getRepository(CareerPlayer::class);
        $playerNameRepository = $this->objectManager->getRepository(PlayerName::class);
        $nationRepository = $this->objectManager->getRepository(Nation::class);

        foreach ($this->csvProcessor->readLine($file) as $row) {
            $playerId = (int) $row['playerid'];

            /** @var Player $currentRecord */
            $player = $playerRepository->findOneBy([
                'gameId' => $playerId,
                'gameVersion' => $import->getCareer()->getGameVersion(),
            ]);

            if (!$player instanceof Player) {
                $nation = $nationRepository->findOneBy([
                    'gameId' => (int) $row['nationality'],
                    'gameVersion' => $import->getCareer()->getGameVersion(),
                ]);

                $firstName = $playerNameRepository->findOneBy([
                    'gameId' => (int) $row['firstnameid'],
                    'gameVersion' => $import->getCareer()->getGameVersion(),
                ]);

                $secondName = $playerNameRepository->findOneBy([
                    'gameId' => (int) $row['lastnameid'],
                    'gameVersion' => $import->getCareer()->getGameVersion(),
                ]);

                $commonName = $playerNameRepository->findOneBy([
                    'gameId' => (int) $row['commonnameid'],
                    'gameVersion' => $import->getCareer()->getGameVersion(),
                ]);

                $player = new Player(
                    $import->getCareer()->getGameVersion(),
                    $playerId,
                    $firstName ? $firstName->getName() : '',
                    $secondName ? $secondName->getName() : '',
                    $commonName ? $commonName->getName() : '',
                    $nation,
                    $row['height'],
                    $row['weight'],
                    FifaDateTransformer::transformToDate($row['birthdate']),
                    (int) $row['preferredposition1']
                );

                $this->objectManager->persist($player);
            }

            /** @var CareerPlayer $careerPlayer */
            $careerPlayer = $careerPlayerRepository->findOneBy([
                'player' => $player,
                'career' => $import->getCareer(),
            ]);

            if (!$careerPlayer instanceof CareerPlayer) {
                $careerPlayer = new CareerPlayer($import->getCareer(), $player);
            }

            $attributes = $careerPlayer->getPlayerAttributes();

            $attributes->setOverall((int) $row['overallrating']);
            $attributes->setGkDiving((int) $row['gkdiving']);
            $attributes->setGkHandling((int) $row['gkhandling']);
            $attributes->setGkKicking((int) $row['gkkicking']);
            $attributes->setGkPositioning((int) $row['gkpositioning']);
            $attributes->setGkReflex((int) $row['gkreflexes']);

            $attributes->setShotPower((int) $row['shotpower']);
            $attributes->setJumping((int) $row['jumping']);
            $attributes->setStamina((int) $row['stamina']);
            $attributes->setStrength((int) $row['strength']);
            $attributes->setLongShots((int) $row['longshots']);

            $attributes->setMarking((int) $row['marking']);
            $attributes->setStandingTackle((int) $row['standingtackle']);
            $attributes->setSlidingTackle((int) $row['slidingtackle']);

            $attributes->setAcceleration((int) $row['acceleration']);
            $attributes->setSprintSpeed((int) $row['sprintspeed']);
            $attributes->setAgility((int) $row['agility']);
            $attributes->setReactions((int) $row['reactions']);
            $attributes->setBalance((int) $row['balance']);

            $attributes->setDribbling((int) $row['dribbling']);
            $attributes->setCurve((int) $row['curve']);
            $attributes->setFreeKickAccuracy((int) $row['freekickaccuracy']);
            $attributes->setLongPassing((int) $row['longpassing']);
            $attributes->setBallControl((int) $row['ballcontrol']);

            $attributes->setAggression((int) $row['aggression']);
            $attributes->setComposure((int) $row['composure']);
            $attributes->setInterceptions((int) $row['interceptions']);
            $attributes->setAttackingPosition((int) $row['attackingworkrate']);
            $attributes->setVision((int) $row['vision']);
            $attributes->setPenalties((int) $row['penalties']);

            $contractExpiresAt = FifaDateTransformer::getFifaDate($row['contractvaliduntil']);
            /* @var PlayerContract $contract */
            if ($careerPlayer->hasActiveContract()) {
                if ($careerPlayer->getActiveContract()->getExpiresAt() !== $contractExpiresAt) {
                    $contract = new PlayerContract($careerPlayer, $contractExpiresAt, 0, true);
                    $careerPlayer->setActiveContract($contract);
                }
            } else {
                $contract = new PlayerContract($careerPlayer, $contractExpiresAt, 0, true);
                $careerPlayer->setActiveContract($contract);
            }

            yield $careerPlayer;
            $this->objectManager->detach($attributes);
            $this->objectManager->detach($contract);
            $this->objectManager->detach($careerPlayer);
            $this->objectManager->detach($player);
        }
    }

    public function supports(Career $career): bool
    {
        return $career->getGameVersion()->getYear() <= 18;
    }

    public function cleanup(): array
    {
        return [
            CareerPlayerAttributes::class,
            PlayerContract::class,
            Player::class,
            CareerPlayer::class,
            Nation::class,
            PlayerName::class,
        ];
    }
}
