<?php


namespace App\Controller\Game\Career;


use App\Entity\Game\Career\Career;
use App\Entity\Game\Career\CareerPlayer;
use App\Entity\Game\Core\Player;
use App\Repository\Game\Career\CareerPlayerRepository;
use App\Response\FractalResponse;
use App\Security\Permissions;
use League\Fractal\Resource\Item;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;

class CareerPlayerController
{
    /** @var CareerPlayerRepository */
    private $careerPlayerRepository;

    /** @var Security */
    private $security;

    public function __construct(
        CareerPlayerRepository $careerPlayerRepository,
        Security $security
    ) {
        $this->security = $security;
        $this->careerPlayerRepository = $careerPlayerRepository;
    }

    /**
     * @Rest\Get("/careers/{career}/players/{player}")
     */
    public function getCareerPlayer(Career $career, Player $player)
    {
        if (!$this->security->isGranted([Permissions::READ], $career)) {
            // throw 404 so we don't expose any information.
            throw new NotFoundHttpException();
        }

        $careerPlayer = $this->careerPlayerRepository->findOneBy([
            'career' => $career,
            'player' => $player,
            'isRetired' => false,
        ]);

        return new FractalResponse(
            new Item($careerPlayer, new CareerTransformer())
        );
    }
}