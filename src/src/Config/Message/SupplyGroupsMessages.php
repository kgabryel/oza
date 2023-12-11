<?php

namespace App\Config\Message;

abstract class SupplyGroupsMessages
{
    public const CREATED_CORRECTLY = 'Nowy grupa zapasów została dodana.';
    public const DELETE_CORRECT = 'Grupa zapasów została usunięta pomyślnie.';
    public const DELETE_INCORRECT = 'Wystąpił błąd podczas usuwania grupy zapasów.';
    public const UPDATE_CORRECT = 'Grupa zapasów została zaktualizowana pomyślnie.';
}
