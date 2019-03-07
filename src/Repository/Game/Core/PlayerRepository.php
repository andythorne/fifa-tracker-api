<?php

namespace App\Repository\Game\Core;

use App\Entity\Game\Core\Player;
use App\Entity\Game\GameVersion;
use App\Repository\Game\Traits\FindByGameTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PlayerRepository extends ServiceEntityRepository
{
    use FindByGameTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function findOneForCareer(GameVersion $gameVersion, int $gameId, bool $regen)
    {
        $builder = $this->createQueryBuilder('e');
        $builder->where('e.gameVersion = :gameVersion', 'e.gameId = :gameId', 'e.realPlayer = :regen');
        $builder->setParameters([
            'gameVersion' => $gameVersion,
            'gameId' => $gameId,
            'regen' => $regen,
        ]);

        return $builder->getQuery()->getOneOrNullResult();
    }
}
