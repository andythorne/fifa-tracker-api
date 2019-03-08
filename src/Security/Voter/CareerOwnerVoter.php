<?php

namespace App\Security\Voter;

use App\Entity\Game\Career\Career;
use App\Security\Permissions;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CareerOwnerVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return $attribute === Permissions::READ;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if ($subject instanceof Career) {
            return $subject->getUser() === $user;
        }

        return false;
    }
}
