<?php

namespace App\Security\Events;

use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserCreatedEvent extends Event
{
    /** @var User */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
