<?php

namespace App\Config\Message\Error;

abstract class QuickListErrors
{
    public const INVALID_NAME = 'Nazwa jest zbyt długa. Powinna mieć {{ limit }} znaków lub mniej.';
    public const INVALID_POSITION = 'Nazwa jest zbyt długa. Powinna mieć {{ limit }} znaków lub mniej.';
    public const INVALID_VALUE = 'Wprowadzono błędną wartość.';
}
