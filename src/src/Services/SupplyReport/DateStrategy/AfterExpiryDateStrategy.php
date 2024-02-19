<?php

namespace App\Services\SupplyReport\DateStrategy;

use DateTime;
use DateTimeInterface;

class AfterExpiryDateStrategy extends DateStrategy
{
    private DateTimeInterface $date;

    public function __construct(DateTimeInterface $date)
    {
        $this->date = $date;
    }

    public function get(): string
    {
        $interval = abs($this->date->diff(new DateTime())->days);
        $date = $this->date->format('d-m-Y');
        if ($interval > 1) {
            return sprintf('%s (%s dni po terminie)', $date, $interval);
        }

        return sprintf('%s (1 dzień po terminie)', $date);
    }
}
