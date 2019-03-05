<?php

namespace App\Entity\Game\Career\Season;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Career\CareerPlayer;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 */
class SeasonPlayerTournamentStats
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
     * @var Career
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Career\Season\Season"
     * )
     */
    private $season;

    /**
     * @var CareerPlayer
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Career\CareerPlayer"
     * )
     */
    private $careerPlayer;

    /**
     * TODO: This key is unknown for now.
     *
     * @var
     */
    private $tournament;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $minutesPlayed;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $avgRating;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $apps;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $goals;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $assists;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $yellowCards;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $redCards;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $cleanSheets;

    public function __construct(Season $season, CareerPlayer $careerPlayer, $tournament = null)
    {
        $this->id = Uuid::uuid4();
        $this->season = $season;
        $this->careerPlayer = $careerPlayer;
        $this->tournament = $tournament;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getSeason(): Career
    {
        return $this->season;
    }

    public function getCareerPlayer(): CareerPlayer
    {
        return $this->careerPlayer;
    }

    public function getTournament()
    {
        return $this->tournament;
    }

    public function getMinutesPlayed(): int
    {
        return $this->minutesPlayed;
    }

    public function setMinutesPlayed(int $minutesPlayed): void
    {
        $this->minutesPlayed = $minutesPlayed;
    }

    public function getAvgRating(): int
    {
        return $this->avgRating;
    }

    public function setAvgRating(int $avgRating): void
    {
        $this->avgRating = $avgRating;
    }

    public function getApps(): int
    {
        return $this->apps;
    }

    public function setApps(int $apps): void
    {
        $this->apps = $apps;
    }

    public function getGoals(): int
    {
        return $this->goals;
    }

    public function setGoals(int $goals): void
    {
        $this->goals = $goals;
    }

    public function getAssists(): int
    {
        return $this->assists;
    }

    public function setAssists(int $assists): void
    {
        $this->assists = $assists;
    }

    public function getYellowCards(): int
    {
        return $this->yellowCards;
    }

    public function setYellowCards(int $yellowCards): void
    {
        $this->yellowCards = $yellowCards;
    }

    public function getRedCards(): int
    {
        return $this->redCards;
    }

    public function setRedCards(int $redCards): void
    {
        $this->redCards = $redCards;
    }

    public function getCleanSheets(): int
    {
        return $this->cleanSheets;
    }

    public function setCleanSheets(int $cleanSheets): void
    {
        $this->cleanSheets = $cleanSheets;
    }
}
