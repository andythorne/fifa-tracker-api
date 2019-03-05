<?php

namespace App\Entity\Game\Import;

use App\Entity\Game\Career\Career;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 */
class Import
{
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
     * @var DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable")
     */
    private $importDate;

    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable")
     */
    private $gameDate;

    /**
     * @var Career
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Career\Career"
     * )
     */
    private $career;

    public function __construct(DateTimeImmutable $importDate, Career $career)
    {
        $this->id = Uuid::uuid4();
        $this->importDate = $importDate;
        $this->career = $career;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getImportDate(): DateTimeImmutable
    {
        return $this->importDate;
    }

    public function getCareer(): Career
    {
        return $this->career;
    }

    public function getGameDate(): DateTimeImmutable
    {
        return $this->gameDate;
    }

    public function setGameDate(DateTimeImmutable $gameDate): void
    {
        $this->gameDate = $gameDate;
    }
}
