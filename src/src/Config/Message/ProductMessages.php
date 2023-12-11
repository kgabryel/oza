<?php

namespace App\Config\Message;

abstract class ProductMessages
{
    public const CREATED_CORRECTLY = 'Nowy produkt został dodany.';
    public const DELETE_CORRECT = 'Produkt został usunięty pomyślnie.';
    public const DELETE_INCORRECT = 'Wystąpił błąd podczas usuwania produktu.';
    public const UPDATE_CORRECT = 'Produkt został zaktualizowany pomyślnie.';
}
