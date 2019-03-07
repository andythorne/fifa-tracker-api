<?php

namespace App\Entity\Game\Core;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Game\GameVersion;
use App\Entity\Game\Traits\GameIdAwareTrait;
use App\Entity\Game\Traits\GameVersionAwareTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 *     attributes={
 *      "normalization_context"={"groups"={"read"}},
 *      "denormalization_context"={"groups"={"write"}}
 *     }
 * )
 *
 * @ORM\Entity(repositoryClass="App\Repository\Game\Core\NationRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"game_version_id", "game_id"})})
 */
class Nation
{
    use GameVersionAwareTrait;
    use GameIdAwareTrait;

    /**
     * @var UuidInterface
     *
     * @ORM\Id
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
     * @var string|null
     *
     * @ORM\Column(type="string", length=2, nullable=true)
     *
     * @Groups({"read"})
     */
    private $isoCountryCode;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Groups({"read"})
     */
    private $topTier;

    public function __construct(GameVersion $gameVersion, int $gameId, string $name, ?string $isoCountryCode, bool $topTier = false)
    {
        $this->id = Uuid::uuid4();
        $this->gameVersion = $gameVersion;
        $this->gameId = $gameId;
        $this->name = $name;
        $this->isoCountryCode = $isoCountryCode;
        $this->topTier = $topTier;
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

    public function getIsoCountryCode(): ?string
    {
        return $this->isoCountryCode;
    }

    public function isTopTier(): bool
    {
        return $this->topTier;
    }

    public function setTopTier(bool $topTier): void
    {
        $this->topTier = $topTier;
    }
}
