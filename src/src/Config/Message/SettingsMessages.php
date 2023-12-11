<?php

namespace App\Config\Message;

abstract class SettingsMessages
{
    public const CHANGED_CORRECTLY = 'Zmieniono pomyślnie.';
    public const DELETE_CORRECT = 'Klucz został usunięty pomyślnie.';
    public const DELETE_INCORRECT = 'Wystąpił błąd podczas usuwania klucza.';
    public const KEY_CREATED_CORRECTLY = 'Klucz został dodany pomyślnie.';
    public const KEY_GENERATED = 'Nowy klucz został wygenerowany.';
    public const KEY_NOT_GENERATED = 'Nowy klucz nie został wygenerowany.';
    public const UPDATED_CORRECTLY = 'Zaktualizowano pomyślnie.';
    public const UPDATE_CORRECT = 'Opis został zaktualizowany pomyślnie.';
    public const UPDATE_INCORRECT = 'Wystąpił błąd podczas aktualizacji klucza.';
}
