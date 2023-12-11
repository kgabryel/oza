<?php

namespace App\Config\Message\Error;

abstract class ApiKeyErrors
{
    public const APPLICATION_MISSING = 'Aplikacja nie została wybrana.';
    public const INVALID_APPLICATION = 'Wybrano błędną aplikację.';
    public const INVALID_VALUE = 'Wprowadzono błędną wartość.';
    public const KEY_MISSING = 'Klucz nie został podany.';
    public const KEY_NOT_UNIQUE = 'Posiadasz już ten klucz przypisany do aplikacji {{ application }}.';
    public const KEY_TOO_LONG = 'Klucz jest zbyt długi. Powinna mieć {{ limit }} znaków lub mniej.';
}
