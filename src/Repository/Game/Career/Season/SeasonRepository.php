<?php

namespace App\Repository\Game\Career\Season;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Career\Season\Season;
use App\Repository\Game\Traits\PaginatedQueryTrait;
use App\Request\Search\SearchRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SeasonRepository extends ServiceEntityRepository
{
    use PaginatedQueryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Season::class);
    }

    public function paginate(SearchRequest $searchRequest, Career $career): Pagerfanta
    {
        $builder = $this->createQueryBuilder('s');
        $builder->where('s.career = :career')
            ->setParameters([
                'career' => $career,
            ]);

        return $this->getPaginateQueryBuilder($searchRequest, $builder);
    }
}
