<?php

namespace App\Entity\Game\Career;

use App\Entity\Game\Core\Player;
use App\Entity\Game\Core\PlayerContract;
use App\Entity\Game\Core\Team;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Game\Career\CareerPlayerRepository")
 */
class CareerPlayer
{
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
     * @var Career
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Career\Career"
     * )
     */
    private $career;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Core\Player"
     * )
     */
    private $player;

    /**
     * @var Team|null
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Core\Team"
     * )
     */
    private $team;

    /**
     * @var CareerPlayerAttributes|null
     *
     * @ORM\OneToOne(
     *     targetEntity="App\Entity\Game\Career\CareerPlayerAttributes",
     *     cascade={"persist", "detach"}
     * )
     */
    private $playerAttributes;

    /**
     * @var PlayerContract|null
     *
     * @ORM\OneToOne(
     *     targetEntity="App\Entity\Game\Core\PlayerContract",
     *     cascade={"persist", "detach"}
     * )
     */
    private $activeContract;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isRetired = false;

    public function __construct(Career $career, Player $player)
    {
        $this->id = Uuid::uuid4();
        $this->career = $career;
        $this->player = $player;
        $this->playerAttributes = new CareerPlayerAttributes();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getCareer(): Career
    {
        return $this->career;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): void
    {
        $this->team = $team;
    }

    public function setPlayerAttributes(CareerPlayerAttributes $playerAttributes): void
    {
        $this->playerAttributes = $playerAttributes;
    }

    public function getPlayerAttributes(): CareerPlayerAttributes
    {
        return $this->playerAttributes;
    }

    public function hasActiveContract(): bool
    {
        return $this->activeContract instanceof PlayerContract;
    }

    public function getActiveContract(): PlayerContract
    {
        return $this->activeContract;
    }

    public function setActiveContract(?PlayerContract $activeContract): void
    {
        if ($this->activeContract instanceof PlayerContract) {
            $this->activeContract->setIsActive(false);
        }

        $this->activeContract = $activeContract;
    }

    public function isRetired(): bool
    {
        return $this->isRetired;
    }

    public function retire(): void
    {
        $this->isRetired = true;
    }
}
