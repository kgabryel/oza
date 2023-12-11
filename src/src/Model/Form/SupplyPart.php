<?php

namespace App\Model\Form;

use App\Entity\Product;
use App\Entity\SupplyPart as Entity;
use App\Entity\Unit;

class SupplyPart
{
    private ?float $amount;
    private ?string $dateOfConsumption;
    private ?string $description;
    private ?bool $open;
    private ?int $part;
    private ?Product $product;
    private ?Unit $unit;

    public function __construct()
    {
        $this->amount = null;
        $this->description = null;
        $this->open = null;
        $this->unit = null;
        $this->product = null;
        $this->dateOfConsumption = null;
        $this->part = null;
    }

    public static function fromEntity(Entity $supplyPart): self
    {
        $model = new self();
        $model->setAmount($supplyPart->getAmount());
        $model->setDescription($supplyPart->getDescription());
        $model->setUnit($supplyPart->getUnit());
        $model->setOpen($supplyPart->isOpen());
        $model->setPart($supplyPart->getPart());
        $model->setDateOfConsumption($supplyPart->getDateOfConsumption()?->format('Y-m-d'));
        $model->setProduct($supplyPart->getProduct());

        return $model;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): void
    {
        $this->unit = $unit;
    }

    public function setOpen(?bool $open): void
    {
        $this->open = $open;
    }

    public function isOpen(): ?bool
    {
        return $this->open;
    }

    public function getPart(): ?int
    {
        return $this->part;
    }

    public function setPart(?int $part): void
    {
        $this->part = $part;
    }

    public function getDateOfConsumption(): ?string
    {
        return $this->dateOfConsumption;
    }

    public function setDateOfConsumption(?string $dateOfConsumption): void
    {
        $this->dateOfConsumption = $dateOfConsumption;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): void
    {
        $this->product = $product;
    }
}
