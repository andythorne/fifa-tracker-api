<?php

namespace App\Controller\Game\Career;

use App\Entity\Game\Career\Career;
use App\Repository\Game\Career\CareerRepository;
use App\Request\Search\SearchRequest;
use App\Response\FractalResponse;
use App\Response\Game\CareerTransformer;
use App\Security\Permissions;
use FOS\RestBundle\Controller\Annotations as Rest;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;

class CareerController
{
    /** @var CareerRepository */
    private $careerRepository;

    /** @var Security */
    private $security;

    public function __construct(
        CareerRepository $careerRepository,
        Security $security
    ) {
        $this->careerRepository = $careerRepository;
        $this->security = $security;
    }

    /**
     * @Rest\Get("/careers/{career}")
     */
    public function getCareer(Career $career)
    {
        if (!$this->security->isGranted([Permissions::READ], $career)) {
            // throw 404 so we don't expose any information.
            throw new NotFoundHttpException();
        }

        return new FractalResponse(
            new Item($career, new CareerTransformer())
        );
    }

    /**
     * @Rest\Get("/careers")
     */
    public function getCareers(SearchRequest $searchRequest)
    {
        $paginator = $this->careerRepository->paginate($searchRequest, $this->security->getUser());

        return new FractalResponse(
            new Collection($paginator->getCurrentPageResults(), new CareerTransformer()),
            $paginator
        );
    }
}
