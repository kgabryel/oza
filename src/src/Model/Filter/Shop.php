<?php

namespace App\Model\Filter;

class Shop
{
    private string $description;
    private string $name;

    public function __construct()
    {
        $this->name = '';
        $this->description = '';
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description ?? '';
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name ?? '';
    }
}
