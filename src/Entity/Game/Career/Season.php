<?php

namespace App\Entity\Game\Career;

use App\Entity\Game\GameIdTrait;
use App\Entity\Game\GameVersionTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 */
class Season
{
    use GameIdTrait;
    use GameVersionTrait;

    /**
     * @var UuidInterface
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    private $yearFrom;
}
