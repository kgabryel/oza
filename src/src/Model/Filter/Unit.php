<?php

namespace App\Model\Filter;

use Doctrine\Common\Collections\ArrayCollection;

class Unit
{
    private string $name;
    private string $shortcut;
    private array $types;
    private ArrayCollection $units;

    public function __construct()
    {
        $this->name = '';
        $this->shortcut = '';
        $this->types = [];
        $this->units = new ArrayCollection();
    }

    public function findMain(): bool
    {
        return in_array(1, $this->types, true);
    }

    public function findSub(): bool
    {
        return in_array(2, $this->types, true);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name ?? '';
    }

    public function getShortcut(): string
    {
        return $this->shortcut;
    }

    public function setShortcut(?string $shortcut): void
    {
        $this->shortcut = $shortcut ?? '';
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    public function setTypes(array $types): void
    {
        $this->types = $types;
    }

    public function getUnits(): ArrayCollection
    {
        return $this->units;
    }

    public function setUnits(ArrayCollection $units): void
    {
        $this->units = $units;
    }
}
