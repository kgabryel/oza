<?php

namespace App\Config\Message\Error;

abstract class ProductsGroupErrors
{
    public const BASE_UNIT_MISSING = 'Jednostka podstawowa nie została wybrana.';
    public const DIFFERENT_UNITS = 'Wybrane grupy produktów mają różne jednostki';
    public const INVALID_BASE_UNIT = 'Wybrano błędną podstawową jednostkę.';
    public const INVALID_UNIT = 'Wybrano błędną jednostkę.';
    public const INVALID_VALUE = 'Wprowadzono błędną wartość.';
    public const NAME_IN_USE = 'Posiadasz już grupę produktów z nazwą: "{{ value }}".';
    public const NAME_MISSING = 'Nazwa nie została podana.';
    public const NAME_TOO_LONG = 'Nazwa jest zbyt długa. Powinna mieć {{ limit }} znaków lub mniej.';
    public const UNIT_MISSING = 'Jednostka nie została wybrana.';
}
