<?php

namespace App\Entity\Game\Import;

use Doctrine\ORM\Mapping as ORM;

trait ImportAwareTrait
{
    /**
     * @var Import
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Game\Import\Import",
     *     inversedBy="id"
     * )
     */
    protected $import;

    /**
     * @return Import
     */
    public function getImport(): Import
    {
        return $this->import;
    }
}
