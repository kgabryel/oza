<?php

namespace App\Model\Form;

use App\Entity\Shop as Entity;

class Shop
{
    private ?string $description;
    private ?string $name;

    public function __construct()
    {
        $this->name = null;
        $this->description = null;
    }

    public static function fromEntity(Entity $entity): self
    {
        $shop = new self();
        $shop->setName($entity->getName());
        $shop->setDescription($entity->getDescription());

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
