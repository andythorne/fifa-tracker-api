<?php

namespace App\Entity\Game\Career;

use App\Entity\Game\GameIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 */
class CareerTeam
{
    use GameIdTrait;

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
     * @var Career
     *
     * @ORM\ManyToOne(
     *     targetEntity="Career",
     *     inversedBy="id"
     * )
     */
    private $career;

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

    public function __construct(Career $career, int $gameId, string $name, int $foundationYear, string $teamColour1, string $teamColour2, string $teamColour3)
    {
        $this->id = Uuid::uuid4();
        $this->career = $career;
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

    public function getCareer(): Career
    {
        return $this->career;
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
