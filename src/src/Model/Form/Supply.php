<?php

namespace App\Model\Form;

use App\Entity\ProductsGroup;
use Doctrine\Common\Collections\ArrayCollection;

class Supply
{
    private ?string $description;
    private ?ProductsGroup $productsGroup;
    private ArrayCollection $supplyGroups;

    public function __construct()
    {
        $this->productsGroup = null;
        $this->description = null;
        $this->supplyGroups = new ArrayCollection();
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getProductsGroup(): ?ProductsGroup
    {
        return $this->productsGroup;
    }

    public function setProductsGroup(?ProductsGroup $productsGroup): void
    {
        $this->productsGroup = $productsGroup;
    }

    public function getSupplyGroups(): ArrayCollection
    {
        return $this->supplyGroups;
    }

    public function setSupplyGroups(ArrayCollection $supplyGroups): void
    {
        $this->supplyGroups = $supplyGroups;
    }
}
