<?php

namespace App\Repository\Game\Traits;

use App\Request\Search\SearchRequest;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

trait PaginatedQueryTrait
{
    protected function getPaginateQueryBuilder(SearchRequest $searchRequest, QueryBuilder $queryBuilder): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($queryBuilder));
        $paginator->setMaxPerPage($searchRequest->getPageSize());
        $paginator->setCurrentPage($searchRequest->getPage());

        return $paginator;
    }
}
