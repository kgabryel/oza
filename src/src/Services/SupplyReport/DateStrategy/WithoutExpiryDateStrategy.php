<?php

namespace App\Services\SupplyReport\DateStrategy;

use DateTimeInterface;

class WithoutExpiryDateStrategy extends DateStrategy
{
    private ?DateTimeInterface $date;

    public function __construct(?DateTimeInterface $date)
    {
        $this->date = $date;
    }

    public function get(): string
    {
        return $this->date
            ?->format('d-m-Y') ?? '';
    }
}
