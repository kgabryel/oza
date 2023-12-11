<?php

namespace App\Config\Message\Error;

abstract class LoginErrors
{
    public const FB_ERROR = 'Wystąpił błąd autoryzacji za pomocą Facebook.';
    public const INVALID_CSRF = 'Błąd logowania.';
    public const INVALID_DATA = 'Błędne dane logowania.';
    public const INVALID_EMAIL_FORMAT = 'Adres E-mail ma nieprawidłową postać.';
    public const REQUIRED_EMAIL = 'Nie wpisano adresu E-mail.';
    public const REQUIRED_PASSWORD = 'Nie wpisano hasła.';
}
