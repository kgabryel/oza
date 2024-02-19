<?php

namespace App\Model\Form;

class ResetPasswordRequest
{
    private ?string $email;

    public function __construct()
    {
        $this->email = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
}
