<?php

namespace App\Repository\Game\Core;

use App\Entity\Game\Core\Nation;
use App\Repository\Game\Traits\FindByGameTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class NationRepository extends ServiceEntityRepository
{
    use FindByGameTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Nation::class);
    }
}
