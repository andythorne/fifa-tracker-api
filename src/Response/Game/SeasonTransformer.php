<?php

namespace App\Response\Game;

use App\Entity\Game\Career\Season\Season;
use League\Fractal\TransformerAbstract;

class SeasonTransformer extends TransformerAbstract
{
    public function transform(Season $season)
    {
        $data = [
            'id' => $season->getId(),
            'currentDate' => $season->getCurrentDate()->format(DATE_ISO8601),
            'year' => $season->getYear(),
        ];

        return $data;
    }
}
