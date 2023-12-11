<?php

namespace App\Model\Filter;

use Doctrine\Common\Collections\ArrayCollection;

class Product
{
    private ?string $barcode;
    private ArrayCollection $brands;
    private ?string $name;
    private ArrayCollection $productsGroupUnits;
    private ArrayCollection $productsGroups;
    private ArrayCollection $units;

    public function __construct()
    {
        $this->name = '';
        $this->units = new ArrayCollection();
        $this->productsGroups = new ArrayCollection();
        $this->productsGroupUnits = new ArrayCollection();
        $this->brands = new ArrayCollection();
        $this->barcode = '';
    }

    public function getBarcode(): string
    {
        return $this->barcode;
    }

    public function setBarcode(?string $barcode): void
    {
        $this->barcode = $barcode ?? '';
    }

    public function getBrands(): ArrayCollection
    {
        return $this->brands;
    }

    public function setBrands(ArrayCollection $brands): void
    {
        $this->brands = $brands;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name ?? '';
    }

    public function getProductsGroupUnits(): ArrayCollection
    {
        return $this->productsGroupUnits;
    }

    public function setProductsGroupUnits(ArrayCollection $units): void
    {
        $this->productsGroupUnits = $units;
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
