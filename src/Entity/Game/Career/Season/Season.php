<?php

namespace App\Entity\Game\Career\Season;

use App\Entity\Game\Career\Career;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Game\Career\Season\SeasonRepository")
 */
class Season
{
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
     * @var Career
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Career\Career",
     *     inversedBy="seasons"
     * )
     */
    private $career;

    /**
     * @var int
     *
     * @ORM\Column(name="season_year", type="integer")
     *
     * @Groups({"read"})
     */
    private $year;

    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(name="season_date", type="datetime_immutable")
     *
     * @Groups({"read"})
     */
    private $currentDate;

    public function __construct(Career $career, int $year)
    {
        $this->id = Uuid::uuid4();
        $this->career = $career;
        $this->year = $year;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getCareer(): Career
    {
        return $this->career;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getCurrentDate(): DateTimeImmutable
    {
        return $this->currentDate;
    }

    public function setCurrentDate(DateTimeImmutable $currentDate): void
    {
        $this->currentDate = $currentDate;
    }
}
