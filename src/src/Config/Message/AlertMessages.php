<?php

namespace App\Config\Message;

abstract class AlertMessages
{
    public const CREATED_CORRECTLY = 'Nowe powiadomienie zostało dodane.';
    public const DELETE_CORRECT = 'Powiadomienie zostało usunięte pomyślnie.';
    public const DELETE_INCORRECT = 'Wystąpił błąd podczas usuwania powiadomienia.';
    public const UPDATE_CORRECT = 'Powiadomienia zostało zaktualizowane pomyślnie.';
}
