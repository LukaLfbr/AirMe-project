<?php

namespace App\Traits;

use Symfony\Bundle\SecurityBundle\Security;

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
