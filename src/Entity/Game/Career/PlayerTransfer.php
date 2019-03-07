<?php

namespace App\Entity\Game\Career;

use App\Entity\Game\Core\Team;
use App\Entity\Game\Import\Import;
use App\Entity\Game\Import\ImportAwareTrait;
use App\Entity\Game\Traits\GameIdAwareTrait;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Game\Career\PlayerTransferRepository")
 */
class PlayerTransfer
{
    use ImportAwareTrait;
    use GameIdAwareTrait;

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
     * @var CareerPlayer
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Career\CareerPlayer"
     * )
     */
    private $careerPlayer;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Core\Team"
     * )
     */
    private $teamFrom;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Core\Team"
     * )
     */
    private $teamTo;

    /**
     * @var CareerPlayer|null
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Career\CareerPlayer"
     * )
     */
    private $exchangePlayer;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $transferFee;

    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(type="date_immutable")
     */
    private $signedAt;

    /**
     * @var DateTimeImmutable|null
     *
     * @ORM\Column(type="date_immutable", nullable=true)
     */
    private $joinedAt;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isPreContract;

    public function __construct(Import $import, int $gameId, CareerPlayer $careerPlayer, ?Team $teamFrom, Team $teamTo, ?CareerPlayer $exchangePlayer, int $transferFee, DateTimeImmutable $signedAt, ?DateTimeImmutable $joinedAt, bool $isPreContract)
    {
        $this->id = Uuid::uuid4();
        $this->import = $import;
        $this->gameId = $gameId;
        $this->careerPlayer = $careerPlayer;
        $this->teamFrom = $teamFrom;
        $this->teamTo = $teamTo;
        $this->exchangePlayer = $exchangePlayer;
        $this->transferFee = $transferFee;
        $this->signedAt = $signedAt;
        $this->joinedAt = $joinedAt;
        $this->isPreContract = $isPreContract;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getCareerPlayer(): CareerPlayer
    {
        return $this->careerPlayer;
    }

    public function getTeamFrom(): ?Team
    {
        return $this->teamFrom;
    }

    public function getTeamTo(): Team
    {
        return $this->teamTo;
    }

    public function getExchangePlayer(): ?CareerPlayer
    {
        return $this->exchangePlayer;
    }

    public function getTransferFee(): int
    {
        return $this->transferFee;
    }

    public function getSignedAt(): DateTimeImmutable
    {
        return $this->signedAt;
    }

    public function getJoinedAt(): ?DateTimeImmutable
    {
        return $this->joinedAt;
    }

    public function isPreContract(): bool
    {
        return $this->isPreContract;
    }
}
