<?php

namespace App\Model\Form;

use App\Entity\Brand;
use App\Entity\Unit;
use Doctrine\Common\Collections\ArrayCollection;

class Product
{
    private ?string $barcode;
    private ?Brand $brand;
    private ?float $defaultAmount;
    private ?string $name;
    private ?string $note;
    private ArrayCollection $productsGroups;
    private ?Unit $unit;

    public function __construct()
    {
        $this->name = null;
        $this->unit = null;
        $this->productsGroups = new ArrayCollection();
        $this->defaultAmount = null;
        $this->note = null;
        $this->brand = null;
        $this->barcode = null;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(?string $barcode): void
    {
        $this->barcode = $barcode;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): void
    {
        $this->brand = $brand;
    }

    public function getDefaultAmount(): ?float
    {
        return $this->defaultAmount;
    }

    public function setDefaultAmount(?float $defaultAmount): void
    {
        $this->defaultAmount = $defaultAmount;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): void
    {
        $this->note = $note;
    }

    public function getProductsGroups(): ArrayCollection
    {
        return $this->productsGroups;
    }

    public function setProductsGroups(ArrayCollection $productsGroups): void
    {
        $this->productsGroups = $productsGroups;
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
