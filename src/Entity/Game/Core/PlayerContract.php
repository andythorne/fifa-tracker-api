<?php

namespace App\Entity\Game\Core;

use App\Entity\Game\Career\CareerPlayer;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 */
class PlayerContract
{
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
     * @var CareerPlayer
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Career\CareerPlayer"
     * )
     */
    protected $careerPlayer;

    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(type="date_immutable")
     */
    protected $expiresAt;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $wage;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $isActive;

    public function __construct(CareerPlayer $careerPlayer, DateTimeImmutable $expiresAt, int $wage, bool $isActive)
    {
        $this->id = Uuid::uuid4();
        $this->careerPlayer = $careerPlayer;
        $this->expiresAt = $expiresAt;
        $this->wage = $wage;
        $this->isActive = $isActive;
    }

    public function getCareerPlayer(): CareerPlayer
    {
        return $this->careerPlayer;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getExpiresAt(): DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function getWage(): int
    {
        return $this->wage;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
