<?php

namespace App\Entity\Game\Core;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 */
class PlayerAttributes
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

    /** @ORM\Column(type="integer") */
    protected $overall;

    /** @ORM\Column(type="integer") */
    protected $gkDiving;
    /** @ORM\Column(type="integer") */
    protected $gkHandling;
    /** @ORM\Column(type="integer") */
    protected $gkKicking;
    /** @ORM\Column(type="integer") */
    protected $gkPositioning;
    /** @ORM\Column(type="integer") */
    protected $gkReflex;

    /** @ORM\Column(type="integer") */
    protected $shotPower;
    /** @ORM\Column(type="integer") */
    protected $jumping;
    /** @ORM\Column(type="integer") */
    protected $stamina;
    /** @ORM\Column(type="integer") */
    protected $strength;
    /** @ORM\Column(type="integer") */
    protected $longShots;

    /** @ORM\Column(type="integer") */
    protected $marking;
    /** @ORM\Column(type="integer") */
    protected $standingTackle;
    /** @ORM\Column(type="integer") */
    protected $slidingTackle;

    /** @ORM\Column(type="integer") */
    protected $acceleration;
    /** @ORM\Column(type="integer") */
    protected $sprintSpeed;
    /** @ORM\Column(type="integer") */
    protected $agility;
    /** @ORM\Column(type="integer") */
    protected $reactions;
    /** @ORM\Column(type="integer") */
    protected $balance;
    /** @ORM\Column(type="integer") */

    /** @ORM\Column(type="integer") */
    protected $dribbling;
    /** @ORM\Column(type="integer") */
    protected $curve;
    /** @ORM\Column(type="integer") */
    protected $freeKickAccuracy;
    /** @ORM\Column(type="integer") */
    protected $longPassing;
    /** @ORM\Column(type="integer") */
    protected $ballControl;

    /** @ORM\Column(type="integer") */
    protected $aggression;
    /** @ORM\Column(type="integer") */
    protected $composure;
    /** @ORM\Column(type="integer") */
    protected $interceptions;
    /** @ORM\Column(type="integer") */
    protected $attackingPosition;
    /** @ORM\Column(type="integer") */
    protected $vision;
    /** @ORM\Column(type="integer") */
    protected $penalties;

    /** @ORM\Column(type="array") */
    protected $traits = [];

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getOverall(): int
    {
        return $this->overall;
    }

    public function setOverall(int $overall): void
    {
        $this->overall = $overall;
    }

    public function getGkDiving(): int
    {
        return $this->gkDiving;
    }

    public function setGkDiving($gkDiving): void
    {
        $this->gkDiving = $gkDiving;
    }

    public function getGkHandling(): int
    {
        return $this->gkHandling;
    }

    public function setGkHandling($gkHandling): void
    {
        $this->gkHandling = $gkHandling;
    }

    public function getGkKicking(): int
    {
        return $this->gkKicking;
    }

    public function setGkKicking($gkKicking): void
    {
        $this->gkKicking = $gkKicking;
    }

    public function getGkPositioning(): int
    {
        return $this->gkPositioning;
    }

    public function setGkPositioning($gkPositioning): void
    {
        $this->gkPositioning = $gkPositioning;
    }

    public function getGkReflex(): int
    {
        return $this->gkReflex;
    }

    public function setGkReflex($gkReflex): void
    {
        $this->gkReflex = $gkReflex;
    }

    public function getShotPower(): int
    {
        return $this->shotPower;
    }

    public function setShotPower($shotPower): void
    {
        $this->shotPower = $shotPower;
    }

    public function getJumping(): int
    {
        return $this->jumping;
    }

    public function setJumping($jumping): void
    {
        $this->jumping = $jumping;
    }

    public function getStamina(): int
    {
        return $this->stamina;
    }

    public function setStamina($stamina): void
    {
        $this->stamina = $stamina;
    }

    public function getStrength(): int
    {
        return $this->strength;
    }

    public function setStrength($strength): void
    {
        $this->strength = $strength;
    }

    public function getLongShots(): int
    {
        return $this->longShots;
    }

    public function setLongShots($longShots): void
    {
        $this->longShots = $longShots;
    }

    public function getMarking(): int
    {
        return $this->marking;
    }

    public function setMarking($marking): void
    {
        $this->marking = $marking;
    }

    public function getStandingTackle(): int
    {
        return $this->standingTackle;
    }

    public function setStandingTackle($standingTackle): void
    {
        $this->standingTackle = $standingTackle;
    }

    public function getSlidingTackle(): int
    {
        return $this->slidingTackle;
    }

    public function setSlidingTackle($slidingTackle): void
    {
        $this->slidingTackle = $slidingTackle;
    }

    public function getAcceleration(): int
    {
        return $this->acceleration;
    }

    public function setAcceleration($acceleration): void
    {
        $this->acceleration = $acceleration;
    }

    public function getSprintSpeed(): int
    {
        return $this->sprintSpeed;
    }

    public function setSprintSpeed($sprintSpeed): void
    {
        $this->sprintSpeed = $sprintSpeed;
    }

    public function getAgility(): int
    {
        return $this->agility;
    }

    public function setAgility($agility): void
    {
        $this->agility = $agility;
    }

    public function getReactions(): int
    {
        return $this->reactions;
    }

    public function setReactions($reactions): void
    {
        $this->reactions = $reactions;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function setBalance($balance): void
    {
        $this->balance = $balance;
    }

    public function getDribbling(): int
    {
        return $this->dribbling;
    }

    public function setDribbling($dribbling): void
    {
        $this->dribbling = $dribbling;
    }

    public function getCurve(): int
    {
        return $this->curve;
    }

    public function setCurve($curve): void
    {
        $this->curve = $curve;
    }

    public function getFreeKickAccuracy(): int
    {
        return $this->freeKickAccuracy;
    }

    public function setFreeKickAccuracy($freeKickAccuracy): void
    {
        $this->freeKickAccuracy = $freeKickAccuracy;
    }

    public function getLongPassing(): int
    {
        return $this->longPassing;
    }

    public function setLongPassing($longPassing): void
    {
        $this->longPassing = $longPassing;
    }

    public function getBallControl(): int
    {
        return $this->ballControl;
    }

    public function setBallControl($ballControl): void
    {
        $this->ballControl = $ballControl;
    }

    public function getAggression(): int
    {
        return $this->aggression;
    }

    public function setAggression($aggression): void
    {
        $this->aggression = $aggression;
    }

    public function getComposure(): int
    {
        return $this->composure;
    }

    public function setComposure($composure): void
    {
        $this->composure = $composure;
    }

    public function getInterceptions(): int
    {
        return $this->interceptions;
    }

    public function setInterceptions($interceptions): void
    {
        $this->interceptions = $interceptions;
    }

    public function getAttackingPosition(): int
    {
        return $this->attackingPosition;
    }

    public function setAttackingPosition($attackingPosition): void
    {
        $this->attackingPosition = $attackingPosition;
    }

    public function getVision(): int
    {
        return $this->vision;
    }

    public function setVision($vision): void
    {
        $this->vision = $vision;
    }

    public function getPenalties(): int
    {
        return $this->penalties;
    }

    public function setPenalties($penalties): void
    {
        $this->penalties = $penalties;
    }

    /**
     * @return string[]
     */
    public function getTraits(): array
    {
        return $this->traits;
    }

    public function setTraits(array $traits): void
    {
        $this->traits = $traits;
    }
}
