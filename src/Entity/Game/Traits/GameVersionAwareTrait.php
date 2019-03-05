<?php

namespace App\Entity\Game\Traits;

use App\Entity\Game\GameVersion;
use Doctrine\ORM\Mapping as ORM;

trait GameVersionAwareTrait
{
    /**
     * @var GameVersion
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\GameVersion"
     * )
     */
    protected $gameVersion;

    public function getGameVersion(): GameVersion
    {
        return $this->gameVersion;
    }
}
