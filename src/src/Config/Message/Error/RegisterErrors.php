<?php

namespace App\Config\Message\Error;

abstract class RegisterErrors
{
    public const DIFFERENT_PASSWORDS = 'Hasła muszą być takie same.';
    public const EMAIL_IN_USE = 'Ten adres E-mail jest już wykorzystywany.';
    public const EMAIL_TOO_LONG = 'E-mail jest zbyt długi. Powinnen mieć {{ limit }} znaków lub mniej.';
    public const EMPTY_EMAIL = 'Adres E-mail nie może być pusty.';
    public const EMPTY_PASSWORD = 'Hasło nie może być puste.';
    public const INVALID_EMAIL_FORMAT = 'Błędny format adresu E-mail.';
    public const INVALID_VALUE = 'Wprowadzono błędną wartość.';
    public const PASSWORD_TOO_LONG = 'Hasło jest zbyt długie. Powinno mieć {{ limit }} znaków lub mniej.';
}
