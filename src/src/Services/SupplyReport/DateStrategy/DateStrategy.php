<?php

namespace App\Services\SupplyReport\DateStrategy;

abstract class DateStrategy
{
    abstract public function get(): string;
}
