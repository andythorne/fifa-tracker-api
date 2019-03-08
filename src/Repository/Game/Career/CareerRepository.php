<?php

namespace App\Repository\Game\Career;

use App\Entity\Game\Career\Career;
use App\Entity\User;
use App\Repository\Game\Traits\FindByGameTrait;
use App\Repository\Game\Traits\PaginatedQueryTrait;
use App\Request\Search\SearchRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CareerRepository extends ServiceEntityRepository
{
    use FindByGameTrait;
    use PaginatedQueryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Career::class);
    }

    public function paginate(SearchRequest $searchRequest, User $user): Pagerfanta
    {
        $builder = $this->createQueryBuilder('c');
        $builder->where('c.user = :user')
            ->setParameters([
                'user' => $user,
            ]);

        return $this->getPaginateQueryBuilder($searchRequest, $builder);
    }
}
