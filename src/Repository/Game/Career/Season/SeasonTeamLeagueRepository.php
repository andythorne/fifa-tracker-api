<?php

namespace App\Repository\Game\Career\Season;

use App\Entity\Game\Career\Season\SeasonTeamLeague;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SeasonTeamLeagueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SeasonTeamLeague::class);
    }
}
