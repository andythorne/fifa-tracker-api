<?php

namespace App\Entity\Game\Traits;

use Doctrine\ORM\Mapping as ORM;

trait GameIdAwareTrait
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $gameId;

    public function getGameId(): int
    {
        return $this->gameId;
    }
}
