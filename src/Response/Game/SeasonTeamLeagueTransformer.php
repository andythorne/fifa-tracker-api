<?php

namespace App\Response\Game;

use App\Entity\Game\Career\Season\SeasonTeamLeague;
use League\Fractal\TransformerAbstract;

class SeasonTeamLeagueTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'team',
        'league',
    ];

    public function transform(SeasonTeamLeague $seasonTeamLeague)
    {
        $data = [
            'id' => $seasonTeamLeague->getId(),
            'position' => $seasonTeamLeague->getPosition(),
            'points' => $seasonTeamLeague->getPoints(),
            'played' => $seasonTeamLeague->getPlayed(),
            
            'homeWon' => $seasonTeamLeague->getHomeWon(),
            'homeDrawn' => $seasonTeamLeague->getHomeDrawn(),
            'homeLost' => $seasonTeamLeague->getHomeLost(),
            'homeGoalsFor' => $seasonTeamLeague->getHomeGoalsFor(),
            'homeGoalsAgainst' => $seasonTeamLeague->getHomeGoalsAgainst(),
            
            'awayWon' => $seasonTeamLeague->getAwayWon(),
            'awayDrawn' => $seasonTeamLeague->getAwayDrawn(),
            'awayLost' => $seasonTeamLeague->getAwayLost(),
            'awayGoalsFor' => $seasonTeamLeague->getAwayGoalsFor(),
            'awayGoalsAgainst' => $seasonTeamLeague->getAwayGoalsAgainst(),
        ];

        return $data;
    }

    public function includeTeam(SeasonTeamLeague $seasonTeamLeague)
    {
        return $this->item($seasonTeamLeague->getTeam(), new TeamTransformer());
    }

    public function includeLeague(SeasonTeamLeague $seasonTeamLeague)
    {
        return $this->item($seasonTeamLeague->getLeague(), new LeagueTransformer());
    }
}
