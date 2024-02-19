<?php

namespace App\Services\SupplyReport\DateStrategy;

use DateTime;
use DateTimeInterface;

class BeforeExpiryDateStrategy extends DateStrategy
{
    private DateTimeInterface $date;

    public function __construct(DateTimeInterface $date)
    {
        $this->date = $date;
    }

    public function get(): string
    {
        $interval = $this->date->diff(new DateTime())->days;
        $date = $this->date->format('d-m-Y');
        if ($interval > 1) {
            return sprintf('%s (%s dni przed terminiem)', $date, $interval);
        }

        return sprintf('%s (1 dzień przed terminiem)', $date);
    }
}
