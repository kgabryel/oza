<?php

namespace App\Config\Message\Error;

abstract class ShoppingErrors
{
    public const AMOUNT_MISSING = 'Ilość nie została podana';
    public const AMOUNT_TOO_SMALL = 'Ilość musi być większy niż 0.';
    public const DISCOUNT_INVALID = 'Rabat musi być mniejszy niż cena.';
    public const DISCOUNT_TOO_SMALL = 'Rabat musi być większy niż 0.';
    public const INVALID_SHOP = 'Wybrano błędny sklep.';
    public const INVALID_SUPPLY = 'Wybrany zapas nie jest poprawny.';
    public const INVALID_UNIT = 'Wybrana jednostka nie jest poprawna.';
    public const INVALID_VALUE = 'Wprowadzono błędną wartość.';
    public const PRICE_MISSING = 'Cena nie została podana';
    public const PRICE_TOO_SMALL = 'Cena musi być większa niż 0.';
    public const SHOP_MISSING = 'Sklep nie został wybrany.';
    public const UNIT_MISSING = 'Jednostka nie została wybrana.';
}
