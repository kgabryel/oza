<?php

namespace App\Model\Form;

use App\Entity\SupplyGroup as Entity;

class SupplyGroup
{
    private ?string $name;

    public function __construct()
    {
        $this->name = null;
    }

    public static function fromEntity(Entity $entity): self
    {
        $shop = new self();
        $shop->setName($entity->getName());

        return $shop;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
