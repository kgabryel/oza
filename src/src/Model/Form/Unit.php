<?php

namespace App\Model\Form;

use App\Entity\Unit as UnitEntity;

class Unit
{
    private ?float $converter;
    private ?bool $isMainUnit;
    private ?UnitEntity $mainUnit;
    private ?string $name;
    private ?string $shortcut;

    public function __construct()
    {
        $this->name = null;
        $this->shortcut = null;
        $this->isMainUnit = false;
        $this->converter = null;
        $this->mainUnit = null;
    }

    public function getConverter(): ?float
    {
        return $this->converter;
    }

    public function setConverter(?float $converter): void
    {
        $this->converter = $converter;
    }

    public function getIsMainUnit(): ?bool
    {
        return $this->isMainUnit;
    }

    public function setIsMainUnit(?bool $isMainUnit): void
    {
        $this->isMainUnit = $isMainUnit;
    }

    public function getMainUnit(): ?UnitEntity
    {
        return $this->mainUnit;
    }

    public function setMainUnit(?UnitEntity $mainUnit): void
    {
        $this->mainUnit = $mainUnit;
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
