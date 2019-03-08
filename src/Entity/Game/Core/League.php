<?php

namespace App\Entity\Game\Core;

use App\Entity\Game\GameVersion;
use App\Entity\Game\Traits\GameIdAwareTrait;
use App\Entity\Game\Traits\GameVersionAwareTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Game\Core\LeagueRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"game_version_id", "game_id"})})
 */
class League
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
     *
     * @Groups({"read"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Groups({"read"})
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     *
     * @Groups({"read"})
     */
    private $tier;

    /**
     * @var Nation|null
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Core\Nation"
     * )
     *
     * @Groups({"read"})
     */
    private $nation;

    public function __construct(GameVersion $gameVersion, int $gameId, string $name, int $tier, ?Nation $nation)
    {
        $this->id = Uuid::uuid4();
        $this->gameVersion = $gameVersion;
        $this->gameId = $gameId;
        $this->name = $name;
        $this->tier = $tier;
        $this->nation = $nation;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTier(): int
    {
        return $this->tier;
    }

    public function getNation(): ?Nation
    {
        return $this->nation;
    }
}
