<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Career\CareerPlayer;
use App\Entity\Game\Career\CareerPlayerAttributes;
use App\Entity\Game\Career\PlayerFake;
use App\Entity\Game\Core\Player;
use App\Entity\Game\Core\PlayerContract;
use App\Entity\Game\Core\PlayerName;
use App\Entity\Game\Import\Import;
use App\Import\CsvProcessor;
use App\Import\Utils\FifaDateTransformer;
use App\Repository\Game\Career\CareerPlayerRepository;
use App\Repository\Game\Career\CareerRepository;
use App\Repository\Game\Career\PlayerFakeRepository;
use App\Repository\Game\Core\NationRepository;
use App\Repository\Game\Core\PlayerNameRepository;
use App\Repository\Game\Core\PlayerRepository;
use Doctrine\Common\Persistence\ObjectManager;

class PlayerImporter implements ImporterInterface
{
    /** @var ObjectManager */
    private $objectManager;

    /** @var PlayerRepository */
    private $playerRepository;

    /** @var PlayerFakeRepository */
    private $playerFakeRepository;

    /** @var PlayerNameRepository */
    private $playerNameRepository;

    /** @var CareerRepository */
    private $careerRepository;

    /** @var CareerPlayerRepository */
    private $careerPlayerRepository;

    /** @var NationRepository */
    private $nationRepository;

    /** @var CsvProcessor */
    private $csvProcessor;

    public function __construct(
        ObjectManager $objectManager,
        PlayerRepository $playerRepository,
        PlayerFakeRepository $playerFakeRepository,
        PlayerNameRepository $playerNameRepository,
        CareerRepository $careerRepository,
        CareerPlayerRepository $careerPlayerRepository,
        NationRepository $nationRepository,
        CsvProcessor $csvProcessor
    ) {
        $this->objectManager = $objectManager;
        $this->playerRepository = $playerRepository;
        $this->playerFakeRepository = $playerFakeRepository;
        $this->playerNameRepository = $playerNameRepository;
        $this->careerRepository = $careerRepository;
        $this->careerPlayerRepository = $careerPlayerRepository;
        $this->nationRepository = $nationRepository;
        $this->csvProcessor = $csvProcessor;
    }

    public function supports(Career $career): bool
    {
        return $career->getGameVersion()->getYear() <= 18;
    }

    public function cleanup(ObjectManager $objectManager): void
    {
        $objectManager->clear(PlayerName::class);
        $objectManager->clear(CareerPlayerAttributes::class);
        $objectManager->clear(PlayerContract::class);
        $objectManager->clear(Player::class);
        $objectManager->clear(CareerPlayer::class);
    }

    public function import(Import $import, string $path)
    {
        // map all the fake players
        $fakePlayerMap = [];
        foreach ($this->csvProcessor->readLine($path.'/editedplayernames.csv') as $row) {
            $fakePlayerMap[(int) $row['playerid']] = $row;
        }

        foreach ($this->csvProcessor->readLine($path.'/players.csv') as $row) {
            $playerId = (int) $row['playerid'];

            /** @var Player $currentRecord */
            $basePlayer = $this->playerRepository->findOneForCareer(
                $import->getCareer()->getGameVersion(),
                $playerId,
                true
            );

            if (!$basePlayer instanceof Player) {
                $nation = $this->nationRepository->findOneByGame(
                    $import->getCareer()->getGameVersion(),
                    (int) $row['nationality']
                );

                $firstName = $this->playerNameRepository->findOneByGame(
                    $import->getCareer()->getGameVersion(),
                    (int) $row['firstnameid']
                );

                $surname = $this->playerNameRepository->findOneByGame(
                    $import->getCareer()->getGameVersion(),
                    (int) $row['lastnameid']
                );

                $commonName = $this->playerNameRepository->findOneByGame(
                    $import->getCareer()->getGameVersion(),
                    (int) $row['commonnameid']
                );

                $basePlayer = new Player(
                    $import->getCareer()->getGameVersion(),
                    $playerId,
                    $firstName ? $firstName->getName() : '',
                    $surname ? $surname->getName() : '',
                    $commonName ? $commonName->getName() : '',
                    $nation,
                    $row['height'],
                    $row['weight'],
                    FifaDateTransformer::transformToDate($row['birthdate']),
                    (int) $row['preferredposition1']
                );

                $this->objectManager->persist($basePlayer);
            }

            // TODO: is this how we identify regens?!
            if (((int) $row['firstnameid']) === 0 && ((int) $row['lastnameid']) === 0) {
                // try find the the current regen

                if (!isset($fakePlayerMap[$playerId])) {
                    throw new \Exception('Cannot find fake player');
                }

                // retire the original CareerPlayer
                $baseCareerPlayer = $this->careerPlayerRepository->findOneBy([
                    'player' => $basePlayer,
                    'career' => $import->getCareer(),
                    'isRetired' => false,
                ]);
                if ($baseCareerPlayer instanceof CareerPlayer) {
                    $baseCareerPlayer->retire();
                    $this->objectManager->persist($baseCareerPlayer);
                }

                $fakePlayerInfo = $fakePlayerMap[$playerId];

                /** @var PlayerName $firstName */
//                $firstName = $this->playerNameRepository->findOneForCareerAndPlayerId(
//                    $import->getCareer()->getGameVersion(),
//                    (int) $fakePlayerInfo['firstnameid']
//                );

                /** @var PlayerName $surname */
//                $surname = $this->playerNameRepository->findOneForCareerAndPlayerId(
//                    $import->getCareer()->getGameVersion(),
//                    (int) $fakePlayerInfo['lastnameid']
//                );

                $regenPlayer = $this->playerFakeRepository->findOneBy([
                    'gameVersion' => $import->getCareer()->getGameVersion(),
                    'gameId' => (int) $row['playerid'],
                    'firstName' => $fakePlayerInfo['firstname'] ?? '',
                    'surname' => $fakePlayerInfo['surname'] ?? '',
                    'realPlayer' => false,
                ]);

                if (!$regenPlayer instanceof PlayerFake) {
//                    $commonName = $this->playerNameRepository->findOneForCareerAndPlayerId(
//                        $import->getCareer()->getGameVersion(),
//                        (int) $fakePlayerInfo['commonnameid']
//                    );

                    $regenPlayer = PlayerFake::fromPlayerReal(
                        $import->getCareer(),
                        $basePlayer,
                        $fakePlayerInfo['firstname'] ?? '',
                        $fakePlayerInfo['surname'] ?? '',
                        $fakePlayerInfo['commonname'] ?? '',
                        FifaDateTransformer::transformToDate($row['birthdate'])
                    );

                    $this->objectManager->persist($regenPlayer);
                }

                $basePlayer = $regenPlayer;
            }

            /** @var CareerPlayer $careerPlayer */
            $careerPlayer = $this->careerPlayerRepository->findOneBy([
                'player' => $basePlayer,
                'career' => $import->getCareer(),
            ]);

            if (!$careerPlayer instanceof CareerPlayer) {
                $careerPlayer = new CareerPlayer($import->getCareer(), $basePlayer);
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
            $contract = null;
            if ($careerPlayer->hasActiveContract()) {
                if ($careerPlayer->getActiveContract()->getExpiresAt() != $contractExpiresAt) {
                    $contract = new PlayerContract($careerPlayer, $contractExpiresAt, 0, true);
                    $careerPlayer->setActiveContract($contract);
                }
            } else {
                $contract = new PlayerContract($careerPlayer, $contractExpiresAt, 0, true);
                $careerPlayer->setActiveContract($contract);
            }

            yield $careerPlayer;
        }
    }
}
