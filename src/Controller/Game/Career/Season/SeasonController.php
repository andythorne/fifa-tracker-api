<?php

namespace App\Controller\Game\Career\Season;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Career\Season\Season;
use App\Repository\Game\Career\Season\SeasonRepository;
use App\Request\Search\SearchRequest;
use App\Response\FractalResponse;
use App\Response\Game\SeasonTransformer;
use App\Security\Permissions;
use FOS\RestBundle\Controller\Annotations as Rest;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;

class SeasonController
{
    /** @var SeasonRepository */
    private $seasonRepository;

    /** @var Security */
    private $security;

    public function __construct(
        SeasonRepository $seasonRepository,
        Security $security
    ) {
        $this->seasonRepository = $seasonRepository;
        $this->security = $security;
    }

    /**
     * @Rest\Get("/seasons/{season}")
     */
    public function getSeason(Season $season)
    {
        if (!$this->security->isGranted([Permissions::READ], $season->getCareer())) {
            throw new NotFoundHttpException();
        }

        return new FractalResponse(
            new Item($season, new SeasonTransformer())
        );
    }

    /**
     * @Rest\Get("/careers/{career}/seasons")
     */
    public function getCareerSeasons(Career $career, SearchRequest $searchRequest)
    {
        if (!$this->security->isGranted([Permissions::READ], $career)) {
            throw new NotFoundHttpException();
        }

        $paginator = $this->seasonRepository->paginate($searchRequest, $career);

        return new FractalResponse(
            new Collection($paginator->getCurrentPageResults(), new SeasonTransformer()),
            $paginator
        );
    }
}
