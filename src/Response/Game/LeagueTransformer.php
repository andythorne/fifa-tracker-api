<?php

namespace App\Response\Game;

use App\Entity\Game\Core\League;
use League\Fractal\TransformerAbstract;

class LeagueTransformer extends TransformerAbstract
{
    public function transform(League $league)
    {
        $data = [
            'id' => $league->getId(),
            'name' => $league->getName(),
            'tier' => $league->getTier(),
        ];

        return $data;
    }
}
