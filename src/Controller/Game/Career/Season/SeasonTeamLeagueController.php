<?php

namespace App\Controller\Game\Career\Season;

use App\Entity\Game\Career\Season\Season;
use App\Entity\Game\Core\League;
use App\Entity\Game\Core\Team;
use App\Repository\Game\Career\Season\SeasonTeamLeagueRepository;
use App\Response\FractalResponse;
use App\Response\Game\SeasonTeamLeagueTransformer;
use App\Security\Permissions;
use FOS\RestBundle\Controller\Annotations as Rest;
use League\Fractal\Resource\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;

class SeasonTeamLeagueController
{
    /** @var SeasonTeamLeagueRepository */
    private $seasonTeamLeagueRepository;

    /** @var Security */
    private $security;

    public function __construct(
        SeasonTeamLeagueRepository $seasonTeamLeagueRepository,
        Security $security
    ) {
        $this->seasonTeamLeagueRepository = $seasonTeamLeagueRepository;
        $this->security = $security;
    }

    /**
     * @Rest\Get("/seasons/{season}/teams/{team}")
     */
    public function getTeamForSeason(Season $season, Team $team)
    {
        if (!$this->security->isGranted([Permissions::READ], $season->getCareer())) {
            // throw 404 so we don't expose any information.
            throw new NotFoundHttpException();
        }

        $seasonTeamLeagues = $this->seasonTeamLeagueRepository->findBy([
            'season' => $season,
            'team' => $team,
        ]);

        return new FractalResponse(
            new Collection($seasonTeamLeagues, new SeasonTeamLeagueTransformer())
        );
    }

    /**
     * @Rest\Get("/seasons/{season}/leagues/{league}")
     */
    public function getLeagueForSeason(Season $season, League $league)
    {
        if (!$this->security->isGranted([Permissions::READ], $season->getCareer())) {
            // throw 404 so we don't expose any information.
            throw new NotFoundHttpException();
        }

        $seasonTeamLeagues = $this->seasonTeamLeagueRepository->findBy([
            'season' => $season,
            'league' => $league,
        ], [
            'position' => 'asc',
        ]);

        return new FractalResponse(
            new Collection($seasonTeamLeagues, new SeasonTeamLeagueTransformer())
        );
    }
}
