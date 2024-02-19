<?php

namespace App\Config\Message;

abstract class SupplyMessages
{
    public const ALERT_ADDED = 'Powiadomienie zostało dodane poprawnie.';
    public const CREATED_CORRECTLY = 'Nowy zapas został dodany.';
    public const DELETE_CORRECT = 'Zapas został usunięty pomyślnie.';
    public const DELETE_INCORRECT = 'Wystąpił błąd podczas usuwania zapasu.';
    public const SUPPLY_UPDATE_ERROR = 'Wystąpił błąd przy łączeniu z zewnętrznym serwisem.';
    public const UPDATE_CORRECT = 'Zapas został zaktualizowany pomyślnie.';
}
