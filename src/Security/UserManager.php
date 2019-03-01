<?php

namespace App\Security;

use App\Entity\User;
use App\Security\Events\Events;
use App\Security\Events\UserCreatedEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserManager
{
    /** @var ObjectManager */
    private $objectManager;

    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(
        ObjectManager $objectManager,
        EncoderFactoryInterface $encoderFactory,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->objectManager = $objectManager;
        $this->encoderFactory = $encoderFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function createUser(string $email, string $rawPassword): User
    {
        // todo: validation

        $user = new User($email);

        $encoder = $this->encoderFactory->getEncoder($user);
        $salt = sha1(random_bytes(20));
        $password = $encoder->encodePassword($rawPassword, $salt);

        $user->setPassword($password);
        $user->setSalt($salt);

        $this->objectManager->persist($user);
        $this->objectManager->flush();

        $event = new UserCreatedEvent($user);
        $this->eventDispatcher->dispatch(Events::USER_CREATED, $event);

        return $user;
    }
}
