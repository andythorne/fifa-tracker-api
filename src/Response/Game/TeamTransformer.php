<?php

namespace App\Response\Game;

use App\Entity\Game\Core\Team;
use League\Fractal\TransformerAbstract;

class TeamTransformer extends TransformerAbstract
{
    public function transform(Team $team)
    {
        $data = [
            'id' => $team->getId(),
            'name' => $team->getName(),
            'foundationYear' => $team->getFoundationYear(),
            'colour1' => $team->getTeamColour1(),
            'colour2' => $team->getTeamColour2(),
            'colour3' => $team->getTeamColour3(),
        ];

        return $data;
    }
}
