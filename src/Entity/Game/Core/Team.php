<?php

namespace App\Entity\Game\Core;

use App\Entity\Game\GameVersion;
use App\Entity\Game\Traits\GameIdAwareTrait;
use App\Entity\Game\Traits\GameVersionAwareTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"game_version_id", "game_id"})})
 */
class Team
{
    use GameVersionAwareTrait;
    use GameIdAwareTrait;

    /**
     * @var UuidInterface
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $foundationYear;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=7)
     */
    private $teamColour1;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=7)
     */
    private $teamColour2;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=7)
     */
    private $teamColour3;

    public function __construct(GameVersion $gameVersion, int $gameId, string $name, int $foundationYear, string $teamColour1, string $teamColour2, string $teamColour3)
    {
        $this->id = Uuid::uuid4();
        $this->gameVersion = $gameVersion;
        $this->gameId = $gameId;
        $this->name = $name;
        $this->foundationYear = $foundationYear;
        $this->teamColour1 = $teamColour1;
        $this->teamColour2 = $teamColour2;
        $this->teamColour3 = $teamColour3;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTeamColour1(): string
    {
        return $this->teamColour1;
    }

    public function getTeamColour2(): string
    {
        return $this->teamColour2;
    }

    public function getTeamColour3(): string
    {
        return $this->teamColour3;
    }
}