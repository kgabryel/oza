<?php

namespace App\Config\Form;

abstract class UnitConfig
{
    public const CONVERTER_HINT = 'Ilość składająca się na jednostkę główną.';
    public const CONVERTER_LABEL = 'Przelicznik';
    public const MAIN_UNIT_LABEL = 'Główna jednostka';
    public const NAME_LABEL = 'Nazwa';
    public const NAME_MAX_LENGTH = 30;
    public const SHORTCUT_LABEL = 'Skrót';
    public const SHORTCUT_MAX_LENGTH = 10;
    public const TYPE_LABEL = 'Typ';
}
