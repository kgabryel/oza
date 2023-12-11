<?php

namespace App\Model\Form;

use App\Entity\Alert as Entity;
use Doctrine\Common\Collections\ArrayCollection;

class Alert
{
    private bool $active;
    private ?string $description;
    private ArrayCollection $types;

    public function __construct()
    {
        $this->description = null;
        $this->types = new ArrayCollection();
        $this->active = false;
    }

    public static function fromEntity(Entity $entity): self
    {
        $alert = new self();
        $alert->setDescription($entity->getDescription());
        if ($entity->isActive()) {
            $alert->activate();
        }
        $alert->setTypes(new ArrayCollection([$entity->getType()]));

        return $alert;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function activate(): self
    {
        $this->active = true;

        return $this;
    }

    public function deactivate(): self
    {
        $this->active = false;

        return $this;
    }

    public function getTypes(): ArrayCollection
    {
        return $this->types;
    }

    public function setTypes(ArrayCollection $types): void
    {
        $this->types = $types;
    }
}
