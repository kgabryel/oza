<?php

namespace App\Validator\CorrectPassword;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraint;

class CorrectPassword extends Constraint
{
    public const PASSWORD_HASHER_OPTION = 'userPasswordHasher';
    public const USER_OPTION = 'user';
    private User $user;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(array $options = [])
    {
        $this->user = $options[self::USER_OPTION];
        $this->userPasswordHasher = $options[self::PASSWORD_HASHER_OPTION];

        parent::__construct(self::clearOptionsArray($options));
    }

    private static function clearOptionsArray(array $options): array
    {
        unset($options[self::USER_OPTION], $options[self::PASSWORD_HASHER_OPTION]);

        return $options;
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
