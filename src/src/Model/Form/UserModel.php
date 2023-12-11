<?php

namespace App\Model\Form;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserModel
{
    private ?string $email;
    private ?string $password;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getUser(UserPasswordHasherInterface $userPasswordHasher): User
    {
        $user = new User();
        $user->setEmail($this->email);
        $user->setPassword($userPasswordHasher->hashPassword($user, $this->password));

        return $user;
    }
}
