<?php

namespace App\Repository\Game\Core;

use App\Entity\Game\Core\Team;
use App\Repository\Game\Traits\FindByGameTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TeamRepository extends ServiceEntityRepository
{
    use FindByGameTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Team::class);
    }
}
