<?php

namespace App\Config\Message\Error;

abstract class UnitErrors
{
    public const CONVERTER_MISSING = 'Przelicznik nie został podany.';
    public const INVALID_CONVERTER = 'Przelicznik musi być większy niż {{ compared_value }}.';
    public const INVALID_UNIT = 'Wybrano błędną jednostkę.';
    public const INVALID_VALUE = 'Wprowadzono błędną wartość.';
    public const NAME_IN_USE = 'Posiadasz już jednostkę z nazwą: "{{ value }}".';
    public const NAME_MISSING = 'Nazwa nie została podana.';
    public const NAME_TOO_LONG = 'Nazwa jest zbyt długa. Powinna mieć {{ limit }} znaków lub mniej.';
    public const SHORTCUT_IN_USE = 'Posiadasz już jednostkę ze skrótem: "{{ value }}".';
    public const SHORTCUT_MISSING = 'Skrót nie został podany.';
    public const SHORTCUT_TOO_LONG = 'Skrót jest zbyt długi. Powinna mieć {{ limit }} znaków lub mniej.';
    public const UNIT_MISSING = 'Jednostka główna nie została wybrana.';
}
