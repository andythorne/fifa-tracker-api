<?php

namespace App\Response\Game;

use App\Entity\Game\Career\Career;
use League\Fractal\TransformerAbstract;

class CareerTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'seasons',
    ];

    public function transform(Career $career)
    {
        $data = [
            'id' => $career->getId(),
            'name' => $career->getName(),
            'managerName' => $career->getFirstName().' '.$career->getSurname(),
            'nationality' => $career->getNationality()->getId(),
        ];

        return $data;
    }

    public function includeSeasons(Career $career)
    {
        $seasons = $career->getSeasons();

        return $this->collection($seasons->toArray(), new SeasonTransformer());
    }
}
