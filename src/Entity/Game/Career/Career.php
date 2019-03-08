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
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Game\Career\CareerRepository")
 */
class Career
{
    // TODO: enable these
//    use BlameableEntity;
//    use TimestampableEntity;
    use GameIdAwareTrait;
    use GameVersionAwareTrait;

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
     * @Groups({"read", "write"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"read"})
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"read"})
     */
    private $surname;

    /**
     * @var Nation
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Core\Nation"
     * )
     * @Groups({"read"})
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
     * @Groups({"read"})
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

    public function getSeasons()
    {
        return $this->seasons;
    }

    public function getCurrentSeason(): Season
    {
        return $this->seasons->last();
    }

    public function currentSeasonOffset(): int
    {
        return count($this->seasons);
    }
}
