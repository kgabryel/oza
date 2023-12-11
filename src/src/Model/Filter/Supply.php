<?php

namespace App\Model\Filter;

use Doctrine\Common\Collections\ArrayCollection;

class Supply
{
    private ?float $amountMax;
    private ?float $amountMin;
    private ArrayCollection $groups;
    private ArrayCollection $productsGroups;
    private ArrayCollection $units;

    public function __construct()
    {
        $this->productsGroups = new ArrayCollection();
        $this->amountMin = null;
        $this->amountMax = null;
        $this->units = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }

    public function getAmountMax(): ?float
    {
        return $this->amountMax;
    }

    public function setAmountMax(?float $amountMax): void
    {
        $this->amountMax = $amountMax;
    }

    public function getAmountMin(): ?float
    {
        return $this->amountMin;
    }

    public function setAmountMin(?float $amountMin): void
    {
        $this->amountMin = $amountMin;
    }

    public function getGroups(): ArrayCollection
    {
        return $this->groups;
    }

    public function setGroups(ArrayCollection $groups): void
    {
        $this->groups = $groups;
    }

    public function getProductsGroups(): ArrayCollection
    {
        return $this->productsGroups;
    }

    public function setProductsGroups(ArrayCollection $productsGroups): void
    {
        $this->productsGroups = $productsGroups;
    }

    public function getUnits(): ArrayCollection
    {
        return $this->units;
    }

    public function setUnits(ArrayCollection $units): void
    {
        $this->units = $units;
    }
}
