<?php

namespace App\Traits;

use Symfony\Bundle\SecurityBundle\Security;

// This trait is used to prevent the error that occurs when using 
// $security->getUser()->getId() instead of this method.
trait UserAwareTrait
{
    private ?int $userId = null;

    public function initializeUser(Security $security): void
    {
        $user = $security->getUser();
        if ($user) {
            $this->userId = $user->getId();
        }
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }
}
