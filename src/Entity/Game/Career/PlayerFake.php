<?php

namespace App\Entity\Game\Career;

use App\Entity\Game\Core\Nation;
use App\Entity\Game\Core\Player;
use App\Entity\Game\GameVersion;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class PlayerFake extends Player
{
    /**
     * @var Career
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Career\Career"
     * )
     */
    private $career;

    public function __construct(GameVersion $gameVersion, int $gameId, Career $career, string $firstName, string $surname, string $commonName, Nation $nationality, int $height, int $weight, DateTimeImmutable $dateOfBirth, string $position)
    {
        parent::__construct($gameVersion, $gameId, $firstName, $surname, $commonName, $nationality, $height, $weight, $dateOfBirth, $position);

        $this->career = $career;
    }

    public function getCareer(): Career
    {
        return $this->career;
    }
}
