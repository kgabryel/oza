<?php

namespace App\Config\Message\Error;

abstract class AlertErrors
{
    public const INVALID_DESCRIPTION = 'Treść nie została podana.';
    public const INVALID_TYPE = 'Wybrano błędny typ.';
    public const INVALID_VALUE = 'Wprowadzono błędną wartość.';
    public const TYPE_MISSING = 'Typ nie został wybrany.';
}
