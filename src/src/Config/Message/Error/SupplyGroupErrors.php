<?php

namespace App\Config\Message\Error;

abstract class SupplyGroupErrors
{
    public const INVALID_VALUE = 'Wprowadzono błędną wartość.';
    public const NAME_IN_USE = 'Posiadasz już grupę z nazwą: "{{ value }}".';
    public const NAME_MISSING = 'Nazwa nie została podana.';
    public const NAME_TOO_LONG = 'Nazwa jest zbyt długa. Powinna mieć {{ limit }} znaków lub mniej.';
}
