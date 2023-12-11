<?php

namespace App\Model\Form;

use App\Entity\Alert as Entity;
use App\Entity\AlertType;

class EditAlert
{
    private bool $active;
    private ?string $description;
    private ?AlertType $type;

    public function __construct()
    {
        $this->description = null;
        $this->type = null;
        $this->active = false;
    }

    public static function fromEntity(Entity $entity): self
    {
        $alert = new self();
        $alert->setDescription($entity->getDescription());
        if ($entity->isActive()) {
            $alert->activate();
        }
        $alert->setType($entity->getType());

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

    public function getType(): ?AlertType
    {
        return $this->type;
    }

    public function setType(?AlertType $type): void
    {
        $this->type = $type;
    }

    public function deactivate(): self
    {
        $this->active = false;

        return $this;
    }
}
