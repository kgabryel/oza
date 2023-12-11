<?php

namespace App\Config\Message\Error;

abstract class SupplyAlertErrors
{
    public const ALERT_MISSING = 'Powiadomienie nie zostało wybrane.';
    public const AMOUNT_IN_USE = 'Do tej wartości już jest przypisane powiadomienie.';
    public const AMOUNT_MISSING = 'Ilość nie została podana.';
    public const AMOUNT_TOO_SMALL = 'Ilość musi być większa niż {{ compared_value }}.';
    public const INVALID_ALERT = 'Wybrano błędne powiadomienie.';
    public const INVALID_UNIT = 'Wybrana jednostka nie jest poprawna.';
    public const INVALID_VALUE = 'Wprowadzono błędną wartość.';
    public const UNIT_MISSING = 'Jednostka jest wymagana.';
}
