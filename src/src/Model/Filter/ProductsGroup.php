<?php

namespace App\Model\Filter;

use Doctrine\Common\Collections\ArrayCollection;

class ProductsGroup
{
    private string $name;
    private ArrayCollection $units;

    public function __construct()
    {
        $this->name = '';
        $this->units = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name ?? '';
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
