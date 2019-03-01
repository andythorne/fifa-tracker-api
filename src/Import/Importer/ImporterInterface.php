<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;

interface ImporterInterface
{
    public function import(Career $career, string $path);

    public function supports(Career $gameVersion);
}
