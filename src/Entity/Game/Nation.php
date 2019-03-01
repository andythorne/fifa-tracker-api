<?php

namespace App\Entity\Game;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 */
class Nation
{
    use GameVersionTrait;
    use GameIdTrait;

    /**
     * @var UuidInterface
     *
     * @ORM\Id
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
     * @var string|null
     *
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $isoCountryCode;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
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
