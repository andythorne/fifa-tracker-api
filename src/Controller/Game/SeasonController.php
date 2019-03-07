<?php

namespace App\Controller\Game;

use App\Entity\Game\Career\Season\Season;
use App\Repository\Game\Career\Season\SeasonRepository;
use App\Repository\Game\Career\Season\SeasonTeamLeagueRepository;
use App\Repository\Game\Core\LeagueRepository;
use Symfony\Component\HttpFoundation\Request;

class SeasonController
{
    /** @var SeasonTeamLeagueRepository */
    private $seasonTeamLeagueRepository;
    /** @var SeasonRepository */
    private $seasonRepository;
    /** @var LeagueRepository */
    private $leagueRepository;

    public function __construct(
        SeasonTeamLeagueRepository $seasonTeamLeagueRepository,
        SeasonRepository $seasonRepository,
        LeagueRepository $leagueRepository
    ) {
        $this->seasonTeamLeagueRepository = $seasonTeamLeagueRepository;
        $this->seasonRepository = $seasonRepository;
        $this->leagueRepository = $leagueRepository;
    }

    public function __invoke(Request $request)
    {
        $results =  $this->seasonTeamLeagueRepository->findBy(
            [
                'season' => $this->seasonRepository->find($request->attributes->get('id')),
                'league' => $this->leagueRepository->find($request->attributes->get('league')),
            ],
            [
                'position' => 'asc'
            ]
        );

        return $results;
    }

//    public function seasonTeam(Season $season, Team $team)
//    {
//        return $this->seasonTeamLeagueRepository->findOneBy(
//            [
//                'season' => $season,
//                'team' => $team,
//            ],
//            [
//                'position' => 'desc'
//            ]
//        );
//    }
}
