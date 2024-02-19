<?php

namespace App\Model\Filter;

use Doctrine\Common\Collections\ArrayCollection;

class SupplyGroup
{
    private string $name;
    private ArrayCollection $productsGroups;

    public function __construct()
    {
        $this->name = '';
        $this->productsGroups = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name ?? '';
    }

    public function getProductsGroups(): ArrayCollection
    {
        return $this->productsGroups;
    }

    public function setProductsGroups(ArrayCollection $productsGroups): void
    {
        $this->productsGroups = $productsGroups;
    }
}
