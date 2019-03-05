<?php

namespace App\Entity\Game\Career;

use App\Entity\Game\Career\Season\Season;
use App\Entity\Game\Core\Nation;
use App\Entity\Game\GameVersion;
use App\Entity\Game\Traits\GameIdAwareTrait;
use App\Entity\Game\Traits\GameVersionAwareTrait;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 */
class Career
{
    use GameIdAwareTrait;
    use GameVersionAwareTrait;

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
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $surname;

    /**
     * @var Nation
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Core\Nation"
     * )
     */
    private $nationality;

    /**
     * @var User
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\User"
     * )
     */
    private $user;

    /**
     * @var Season[]
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Game\Career\Season\Season",
     *     mappedBy="career"
     * )
     */
    private $seasons;

    public function __construct(GameVersion $gameVersion, User $user)
    {
        $this->id = Uuid::uuid4();
        $this->gameVersion = $gameVersion;
        $this->user = $user;
        $this->seasons = new ArrayCollection();
    }

    public function updateFromSave(int $gameId, string $firstName, string $surname, Nation $nationality)
    {
        $this->gameId = $gameId;
        $this->firstName = $firstName;
        $this->surname = $surname;
        $this->nationality = $nationality;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function getNationality(): ?Nation
    {
        return $this->nationality;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getSeasons(): array
    {
        return $this->seasons;
    }

    public function getCurrentSeason(): Season
    {
        return end($this->seasons);
    }
}
