<?php

namespace App\Entity\Game;

use Doctrine\ORM\Mapping as ORM;

trait GameIdTrait
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
