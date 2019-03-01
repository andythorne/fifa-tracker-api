<?php

namespace App\Controller\User;

use App\Security\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController
{
    /** @var UserManager */
    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @Route(
     *     name="register",
     *     path="/register",
     *     methods={"POST"}
     * )
     */
    public function register(Request $request)
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $newUser = $this->userManager->createUser($email, $password);
    }
}
