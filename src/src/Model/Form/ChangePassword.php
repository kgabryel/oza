<?php

namespace App\Model\Form;

class ChangePassword
{
    private ?string $newPassword;
    private ?string $oldPassword;

    public function __construct()
    {
        $this->oldPassword = null;
        $this->newPassword = null;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(?string $newPassword): void
    {
        $this->newPassword = $newPassword;
    }

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(?string $oldPassword): void
    {
        $this->oldPassword = $oldPassword;
    }
}
