<?php

namespace App\Config\Message\Error;

abstract class ResetPasswordErrors
{
    public const INVALID_PASSWORD = 'Hasło jest niepoprawne';
    public const INVALID_TOKEN = 'Podany token jest błędny lub wygasł.';
    public const SAME_PASSWORD = 'Nowe hasło jest takie samo jak stare';
}
