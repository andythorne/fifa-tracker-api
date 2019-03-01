<?php

namespace App\Entity\Game;

use Doctrine\ORM\Mapping as ORM;

trait GameVersionTrait
{
    /**
     * @var GameVersion
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\GameVersion",
     *     inversedBy="id"
     * )
     */
    protected $gameVersion;

    public function getGameVersion(): GameVersion
    {
        return $this->gameVersion;
    }
}
