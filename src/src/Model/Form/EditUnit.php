<?php

namespace App\Model\Form;

class EditUnit
{
    private ?string $name;
    private ?string $shortcut;

    public function __construct()
    {
        $this->name = null;
        $this->shortcut = null;
    }

    public static function fromEntity($entity): self
    {
        $unit = new self();
        $unit->setName($entity->getName());
        $unit->setShortcut($entity->getShortcut());

        return $unit;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getShortcut(): ?string
    {
        return $this->shortcut;
    }

    public function setShortcut(?string $shortcut): void
    {
        $this->shortcut = $shortcut;
    }
}
