<?php

namespace App\Config\Message\Error;

abstract class ShoppingListErrors
{
    public const AMOUNT_MISSING = 'Ilość nie została podana';
    public const AMOUNT_TOO_SMALL = 'Ilość musi być większy niż {{ compared_value }}.';
    public const INVALID_PRODUCT = 'Wybrano błędny produkt.';
    public const INVALID_PRODUCTS_GROUP = 'Wybrano błędną grupę produktów.';
    public const INVALID_SHOP = 'Wybrany sklep nie jest poprawna.';
    public const INVALID_UNIT = 'Wybrana jednostka nie jest poprawna.';
    public const INVALID_VALUE = 'Wprowadzono błędną wartość.';
    public const NAME_TOO_LONG = 'Nazwa jest zbyt długa. Powinna mieć {{ limit }} znaków lub mniej.';
    public const UNIT_MISSING = 'Jednostka nie została wybrana.';
}
