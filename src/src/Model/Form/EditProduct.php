<?php

namespace App\Model\Form;

use App\Entity\Brand;
use App\Entity\Product;
use App\Entity\Unit;
use Doctrine\Common\Collections\ArrayCollection;

class EditProduct
{
    private ?string $barcode;
    private ?Brand $brand;
    private ?string $name;
    private ?string $note;
    private ArrayCollection $productsGroups;
    private ?Unit $unit;

    public function __construct()
    {
        $this->name = null;
        $this->note = null;
        $this->unit = null;
        $this->brand = null;
        $this->productsGroups = new ArrayCollection();
        $this->barcode = null;
    }

    public static function fromEntity(Product $entity): self
    {
        $product = new self();
        $product->setName($entity->getName());
        $product->setNote($entity->getNote());
        $product->setUnit($entity->getUnit());
        $product->setProductsGroups(new ArrayCollection($entity->getGroups()->toArray()));
        $product->setBrand($entity->getBrand());
        $product->setBarcode($entity->getBarcode());

        return $product;
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

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): void
    {
        $this->unit = $unit;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): void
    {
        $this->brand = $brand;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(?string $barcode): void
    {
        $this->barcode = $barcode;
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
