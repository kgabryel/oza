<?php

namespace App\Config\Form;

abstract class RegisterConfig
{
    public const EMAIL_LABEL = 'Adres E-mail';
    public const EMAIL_MAX_LENGTH = 180;
    public const PASSWORD_LABEL = 'Hasło';
    public const PASSWORD_MAX_LENGTH = 255;
    public const PASSWORD_REPEAT_LABEL = 'Powtórz hasło';
}
