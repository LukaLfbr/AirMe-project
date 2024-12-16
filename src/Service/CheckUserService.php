<?php

namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class CheckUserService
{
    private $security;
    private $translator;

    public function __construct(Security $security, TranslatorInterface $translator,)
    {
        $this->security = $security;
        $this->translator = $translator;
    }

    public function checkUser(): response
    {
        if (!$this->security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_login');
        }
    }

    public function checkAdmin(): response
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            throw new \Exception($this->translator->trans('admin.login.failure'));
        }
    }
}
