<?php

namespace App\Config\Message\Error;

abstract class SupplyErrors
{
    public const AMOUNT_MISSING = 'Ilość nie została podana.';
    public const AMOUNT_TOO_SMALL = 'Ilość musi być większa niż {{ compared_value }}.';
    public const INVALID_PRODUCT = 'Wybrano błędny produkt.';
    public const INVALID_PRODUCTS_GROUP = 'Wybrano błędną grupę produktów.';
    public const INVALID_SUPPLIES_GROUP = 'Wybrano błędną grupę zapasów.';
    public const INVALID_UNIT = 'Wybrano błędną jednostkę.';
    public const INVALID_VALUE = 'Wprowadzono błędną wartość.';
    public const PART_MISSING = 'Ilość części nie została wybrana.';
    public const PART_TOO_SMALL = 'Ilość części musi być większa niż {{ compared_value }}.';
    public const PRODUCTS_GROUP_MISSING = 'Grupa produktów nie została wybrana.';
    public const UNIT_MISSING = 'Jednostka nie została wybrana.';
}
