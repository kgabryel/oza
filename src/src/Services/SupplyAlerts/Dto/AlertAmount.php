<?php

namespace App\Services\SupplyAlerts\Dto;

use App\Entity\SupplyAlert;
use App\Entity\Unit;
use App\Utils\UnitUtils;

class AlertAmount
{
    private float $amount;
    private int $index;

    public function __construct(int $index, SupplyAlert $alert, Unit $baseUnit)
    {
        $this->index = $index;
        $this->amount = UnitUtils::parseAmount(
            $alert->getAmount(),
            $alert->getUnit(),
            $baseUnit
        );
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getIndex(): int
    {
        return $this->index;
    }
}
