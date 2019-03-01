<?php

namespace App\Entity\Game;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 */
class GameVersion
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
     * @var int
     *
     * @ORM\Column(type="integer", length=2)
     */
    private $year;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    public function __construct(int $year)
    {
        $this->id = Uuid::uuid4();
        $this->year = $year;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }
}
