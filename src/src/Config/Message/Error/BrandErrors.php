<?php

namespace App\Config\Message\Error;

abstract class BrandErrors
{
    public const BRAND_IN_USE = 'Posiadasz już markę z nazwą: "{{ value }}".';
    public const INVALID_VALUE = 'Wprowadzono błędną wartość.';
    public const NAME_MISSING = 'Nazwa nie została podana.';
    public const NAME_TOO_LONG = 'Nazwa jest zbyt długa. Powinna mieć {{ limit }} znaków lub mniej.';
}
