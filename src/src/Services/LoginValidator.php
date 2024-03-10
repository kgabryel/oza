<?php

namespace App\Services;

use App\Config\Message\Error\LoginErrors;
use Symfony\Component\HttpFoundation\Request;

class LoginValidator
{
    private array $errors;
    private bool $invalidEmail;
    private bool $invalidPassword;

    public function __construct()
    {
        $this->errors = [
            'email' => [],
            'password' => [],
            'login' => []
        ];
        $this->invalidEmail = false;
        $this->invalidPassword = false;
    }

    public function checkCredentials(array $credentials): bool
    {
        $validEmail = $this->checkEmail($credentials['email']);
        $validPassword = $this->checkPassword($credentials['password']);

        return $validPassword && $validEmail;
    }

    private function checkEmail(?string $email): bool
    {
        if ($email === null || $email === '') {
            $this->invalidEmail = true;
            $this->errors['email'] = LoginErrors::REQUIRED_EMAIL;

            return false;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->invalidEmail = true;
            $this->errors['email'] = LoginErrors::INVALID_EMAIL_FORMAT;

            return false;
        }

        return true;
    }

    private function checkPassword(?string $password): bool
    {
        if ($password === null || $password === '') {
            $this->invalidPassword = true;
            $this->errors['password'] = LoginErrors::REQUIRED_PASSWORD;

            return false;
        }

        return true;
    }

    public function setErrors(Request $request): void
    {
        $flashBag = $request->getSession()->getFlashBag();
        $flashBag->set('loginErrors', $this->errors['login']);
        $flashBag->set('emailErrors', $this->errors['email']);
        $flashBag->set('passwordErrors', $this->errors['password']);
        $flashBag->set('invalidEmail', $this->invalidEmail);
        $flashBag->set('invalidPassword', $this->invalidPassword);
    }

    public function setLoginError(string $error): void
    {
        $this->invalidEmail = true;
        $this->invalidPassword = true;
        $this->errors['login'] = $error;
    }

    public function setPasswordError(string $error): void
    {
        $this->invalidEmail = true;
        $this->invalidPassword = true;
        $this->errors['password'] = $error;
    }
}
