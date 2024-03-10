<?php

namespace App\Validator\CorrectPassword;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraint;

class CorrectPassword extends Constraint
{
    private User $user;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(User $user, UserPasswordHasherInterface $userPasswordHasher, array $options = [])
    {
        $this->user = $user;
        $this->userPasswordHasher = $userPasswordHasher;

        parent::__construct($options);
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getUserPasswordHasher(): UserPasswordHasherInterface
    {
        return $this->userPasswordHasher;
    }
}
