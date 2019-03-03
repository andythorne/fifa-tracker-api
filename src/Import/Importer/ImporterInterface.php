<?php

namespace App\Import\Importer;

use App\Entity\Game\Career;
use App\Entity\Game\Import\Import;

interface ImporterInterface
{
    public function import(Import $import, string $path);

    public function supports(Career $career);
}
