<?php

namespace App\Repository\Game\Traits;

use App\Entity\Game\GameVersion;

trait FindByGameTrait
{
    /**
     * Find an entity by GameVersion and GameId.
     *
     * @return object|null
     */
    public function findOneByGame(GameVersion $gameVersion, int $gameId)
    {
        $builder = $this->createQueryBuilder('e');
        $builder->where('e.gameVersion = :gameVersion', 'e.gameId = :gameId');
        $builder->setParameters([
                'gameVersion' => $gameVersion,
                'gameId' => $gameId,
            ])
        ;

        return $builder->getQuery()->getOneOrNullResult();
    }
}
