<?php

namespace App\Entity\Game;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 */
class SavedGame
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var GameVersion
     *
     * @ORM\ManyToOne(
     *     targetEntity="GameVersion",
     *     inversedBy="id"
     * )
     */
    private $gameVersion;
}
