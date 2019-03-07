<?php

namespace App\Repository\Game\Core;

use App\Entity\Game\Core\League;
use App\Repository\Game\Traits\FindByGameTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class LeagueRepository extends ServiceEntityRepository
{
    use FindByGameTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, League::class);
    }
}
