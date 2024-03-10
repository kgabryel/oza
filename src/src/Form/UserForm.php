<?php

namespace App\Form;

use App\Entity\User;
use App\Services\UserService;
use Symfony\Component\Form\AbstractType;

abstract class UserForm extends AbstractType
{
    protected User $user;

    public function __construct(UserService $userService)
    {
        $this->user = $userService->getUser();
    }
}
