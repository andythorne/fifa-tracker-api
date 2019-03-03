<?php

namespace App\Entity\Game;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 */
class Career
{
    use GameIdTrait;
    use GameVersionTrait;

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
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $surname;

    /**
     * @var Nation
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Nation",
     *     inversedBy="id"
     * )
     */
    private $nationality;

    /**
     * @var User
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\User",
     *     inversedBy="id"
     * )
     */
    private $user;

    public function __construct(GameVersion $gameVersion, User $user)
    {
        $this->id = Uuid::uuid4();
        $this->gameVersion = $gameVersion;
        $this->user = $user;
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
}
