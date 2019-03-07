<?php

namespace App\Entity\Game\Career\Season;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Game\Core\League;
use App\Entity\Game\Core\Team;
use App\Entity\Game\Import\Import;
use App\Entity\Game\Import\ImportAwareTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={
 *          "season_league"={
 *             "method"="GET",
 *             "path"="/seasons/{id}/leagues/{league}",
 *             "controller"=App\Controller\Game\SeasonController::class,
 *             "defaults"={
 *              "_api_receive"=false,
 *             },
 *          },
 *          "seasonTeam"={
 *             "method"="GET",
 *             "path"="/seasons/{id}/teams/{team}",
 *             "controller"=App\Controller\Game\SeasonController::class,
 *             "defaults"={
 *              "_api_receive"=false,
 *             },
 *          }
 *     },
 *     attributes={
 *      "normalization_context"={"groups"={"read"}},
 *      "denormalization_context"={"groups"={"write"}}
 *     }
 * )
 *
 * @ORM\Entity(repositoryClass="App\Repository\Game\Career\Season\SeasonTeamLeagueRepository")
 */
class SeasonTeamLeague
{
    use ImportAwareTrait;

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
     * @var Season
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Career\Season\Season"
     * )
     */
    private $season;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Core\Team"
     * )
     *
     * @Groups({"read"})
     */
    private $team;

    /**
     * @var League
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Core\League"
     * )
     *
     * @Groups({"read"})
     */
    private $league;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     *
     * @Groups({"read"})
     */
    private $position;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $played = 0;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $homeWon = 0;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $homeDrawn = 0;
    /**
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $homeLost = 0;
    /**
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $homeGoalsFor = 0;
    /**
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $homeGoalsAgainst = 0;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $awayWon = 0;
    /**
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $awayDrawn = 0;
    /**
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $awayLost = 0;
    /**
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $awayGoalsFor = 0;
    /**
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $awayGoalsAgainst = 0;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $points = 0;

    public function __construct(Import $import, Season $season, Team $team, League $league)
    {
        $this->id = Uuid::uuid4();
        $this->import = $import;
        $this->season = $season;
        $this->team = $team;
        $this->league = $league;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getSeason(): Season
    {
        return $this->season;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function getLeague(): League
    {
        return $this->league;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getPlayed(): int
    {
        return $this->played;
    }

    public function setPlayed(int $played): void
    {
        $this->played = $played;
    }

    public function getHomeWon(): int
    {
        return $this->homeWon;
    }

    public function setHomeWon(int $homeWon): void
    {
        $this->homeWon = $homeWon;
    }

    public function getHomeDrawn(): int
    {
        return $this->homeDrawn;
    }

    public function setHomeDrawn(int $homeDrawn): void
    {
        $this->homeDrawn = $homeDrawn;
    }

    public function getHomeLost(): int
    {
        return $this->homeLost;
    }

    public function setHomeLost(int $homeLost): void
    {
        $this->homeLost = $homeLost;
    }

    public function getHomeGoalsFor(): int
    {
        return $this->homeGoalsFor;
    }

    public function setHomeGoalsFor(int $homeGoalsFor): void
    {
        $this->homeGoalsFor = $homeGoalsFor;
    }

    public function getHomeGoalsAgainst(): int
    {
        return $this->homeGoalsAgainst;
    }

    public function setHomeGoalsAgainst(int $homeGoalsAgainst): void
    {
        $this->homeGoalsAgainst = $homeGoalsAgainst;
    }

    public function getAwayWon(): int
    {
        return $this->awayWon;
    }

    public function setAwayWon(int $awayWon): void
    {
        $this->awayWon = $awayWon;
    }

    public function getAwayDrawn(): int
    {
        return $this->awayDrawn;
    }

    public function setAwayDrawn(int $awayDrawn): void
    {
        $this->awayDrawn = $awayDrawn;
    }

    public function getAwayLost(): int
    {
        return $this->awayLost;
    }

    public function setAwayLost(int $awayLost): void
    {
        $this->awayLost = $awayLost;
    }

    public function getAwayGoalsFor(): int
    {
        return $this->awayGoalsFor;
    }

    public function setAwayGoalsFor(int $awayGoalsFor): void
    {
        $this->awayGoalsFor = $awayGoalsFor;
    }

    public function getAwayGoalsAgainst(): int
    {
        return $this->awayGoalsAgainst;
    }

    public function setAwayGoalsAgainst(int $awayGoalsAgainst): void
    {
        $this->awayGoalsAgainst = $awayGoalsAgainst;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function setPoints(int $points): void
    {
        $this->points = $points;
    }
}
