<?php

namespace App\Model\Form;

use App\Entity\ProductsGroup;
use App\Entity\Unit;

class EditProductsGroup
{
    private ?Unit $baseUnit;
    private ?string $name;
    private ?string $note;

    public function __construct()
    {
        $this->name = null;
        $this->note = null;
        $this->baseUnit = null;
    }

    public static function fromEntity(ProductsGroup $entity): self
    {
        $group = new self();
        $group->setName($entity->getName());
        $group->setBaseUnit($entity->getBaseUnit());
        $group->setNote($entity->getNote());

        return $group;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getBaseUnit(): ?Unit
    {
        return $this->baseUnit;
    }

    public function setBaseUnit(?Unit $baseUnit): void
    {
        $this->baseUnit = $baseUnit;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): void
    {
        $this->note = $note;
    }
}
