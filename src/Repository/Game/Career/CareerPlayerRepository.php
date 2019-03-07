<?php

namespace App\Repository\Game\Career;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Career\CareerPlayer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CareerPlayerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CareerPlayer::class);
    }

    public function findOneForCareerAndPlayerId(Career $career, int $playerId): ?CareerPlayer
    {
        $builder = $this->createQueryBuilder('cp');
        $builder
            ->innerJoin('cp.player', 'p')
            ->where('cp.isRetired = false', 'p.gameId = :gameId', 'cp.career = :career')
            ->setParameters([
                'career' => $career,
                'gameId' => $playerId,
            ])
        ;

        return $builder->getQuery()->getOneOrNullResult();
    }
}
