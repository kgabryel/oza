<?php

namespace App\Model\Filter;

use Doctrine\Common\Collections\ArrayCollection;

class Alert
{
    private string $description;
    private array $statuses;
    private ArrayCollection $types;

    public function __construct()
    {
        $this->description = '';
        $this->types = new ArrayCollection();
        $this->statuses = [];
    }

    public function findActive(): bool
    {
        return in_array(1, $this->statuses, true);
    }

    public function findInactive(): bool
    {
        return in_array(2, $this->statuses, true);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description ?? '';
    }

    public function getStatuses(): array
    {
        return $this->statuses;
    }

    public function setStatuses(array $statuses): void
    {
        $this->statuses = $statuses;
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
