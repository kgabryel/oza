<?php

namespace App\Config\Message\Error;

abstract class ProductErrors
{
    public const BARCODE_IN_USE = 'Posiadasz już produkt z kodem kreskowym: "{{ value }}".';
    public const BARCODE_TOO_LONG = 'Kod kreskowy jest za długi.';
    public const INVALID_AMOUNT = 'Domyślna ilość musi być większa niż 0.';
    public const INVALID_BRAND = 'Wybrana marka nie jest poprawna.';
    public const INVALID_PRODUCTS_GROUP = 'Wybrana grupa produktów nie jest poprawna.';
    public const INVALID_UNIT = 'Wybrana jednostka nie jest poprawna.';
    public const INVALID_VALUE = 'Wprowadzono błędną wartość.';
    public const NAME_MISSING = 'Nazwa nie została podana.';
    public const NAME_TOO_LONG = 'Nazwa jest zbyt długa. Powinna mieć {{ limit }} znaków lub mniej.';
    public const NAME_WITHOUT_BRAND_IN_USE = 'Posiadasz już produkt z nazwą: "{{ name }}".';
    public const NAME_WITH_BRAND_IN_USE = 'Posiadasz już produkt z nazwą: "{{ name }}" dla marki "{{ brand }}".';
    public const PRODUCTS_GROUP_MISSING = 'Produkt musi należeć do przynajmniej jednego grupy produktów';
    public const UNIT_MISSING = 'Jednostka jest wymagana.';
}
