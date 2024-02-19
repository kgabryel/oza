<?php

namespace App\Model\Form;

use App\Entity\ProductsGroup as Entity;
use App\Entity\Unit;

class ProductsGroup
{
    private ?bool $createSupply;
    private ?string $name;
    private ?string $note;
    private ?Unit $unit;

    public function __construct()
    {
        $this->name = null;
        $this->unit = null;
        $this->note = null;
        $this->createSupply = false;
    }

    public static function fromEntity(Entity $entity): self
    {
        $product = new self();
        $product->setName($entity->getName());
        $product->setNote($entity->getNote());
        $product->setUnit($entity->getUnit());

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

    public function getCreateSupply(): ?bool
    {
        return $this->createSupply;
    }

    public function setCreateSupply(?bool $createSupply): void
    {
        $this->createSupply = $createSupply;
    }
}
