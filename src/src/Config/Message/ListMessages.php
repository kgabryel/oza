<?php

namespace App\Config\Message;

abstract class ListMessages
{
    public const CREATED_CORRECTLY = 'Nowa lista została dodana.';
    public const DELETE_CORRECT = 'Lista została usunięta pomyślnie.';
    public const DELETE_INCORRECT = 'Wystąpił błąd podczas usuwania listy.';
    public const UPDATE_CORRECT = 'Aktualizacja listy przebiegła poprawnie.';
}
