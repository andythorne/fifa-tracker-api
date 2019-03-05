<?php

namespace App\Entity\Game\Core;

use App\Entity\Game\GameVersion;
use App\Entity\Game\Traits\GameIdAwareTrait;
use App\Entity\Game\Traits\GameVersionAwareTrait;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"game_version_id", "game_id"})})
 */
class Player
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
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $surname;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $commonName;

    /**
     * @var Nation
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Core\Nation"
     * )
     */
    protected $nationality;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $height;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $weight;

    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(type="date_immutable")
     */
    protected $dateOfBirth;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3)
     */
    protected $position;

    public function __construct(GameVersion $gameVersion, int $gameId, ?string $firstName, ?string $surname, ?string $commonName, Nation $nationality, int $height, int $weight, DateTimeImmutable $dateOfBirth, string $position)
    {
        $this->id = Uuid::uuid4();
        $this->gameVersion = $gameVersion;
        $this->gameId = $gameId;
        $this->firstName = $firstName;
        $this->surname = $surname;
        $this->commonName = $commonName;
        $this->nationality = $nationality;
        $this->height = $height;
        $this->weight = $weight;
        $this->dateOfBirth = $dateOfBirth;
        $this->position = $position;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getCommonName(): string
    {
        return $this->commonName;
    }

    public function getNationality(): Nation
    {
        return $this->nationality;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getDateOfBirth(): DateTimeImmutable
    {
        return $this->dateOfBirth;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition(string $position): void
    {
        $this->position = $position;
    }
}
