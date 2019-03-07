<?php

namespace App\Repository\Game\Career;

use App\Entity\Game\Career\Career;
use App\Repository\Game\Traits\FindByGameTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CareerRepository extends ServiceEntityRepository
{
    use FindByGameTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Career::class);
    }
}
