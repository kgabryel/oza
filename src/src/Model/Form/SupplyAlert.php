<?php

namespace App\Model\Form;

use App\Entity\Alert as AlertEntity;
use App\Entity\Supply;
use App\Entity\Unit;

class SupplyAlert
{
    private ?AlertEntity $alert;
    private ?float $amount;
    private ?Supply $supply;
    private ?Unit $unit;

    public function __construct()
    {
        $this->amount = 0;
        $this->alert = null;
        $this->unit = null;
        $this->supply = null;
    }

    public function getAlert(): ?AlertEntity
    {
        return $this->alert;
    }

    public function setAlert(?AlertEntity $alert): void
    {
        $this->alert = $alert;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): void
    {
        if ($amount === null) {
            $amount = 0.0;
        }
        $this->amount = $amount;
    }

    public function getSupply(): ?Supply
    {
        return $this->supply;
    }

    public function setSupply(?Supply $supply): void
    {
        $this->supply = $supply;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): void
    {
        $this->unit = $unit;
    }
}
