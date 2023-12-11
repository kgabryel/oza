<?php

namespace App\Model\Form;

use App\Entity\Supply;
use Doctrine\Common\Collections\ArrayCollection;

class EditSupply
{
    private ?string $description;
    private ArrayCollection $supplyGroups;

    public function __construct()
    {
        $this->description = null;
        $this->supplyGroups = new ArrayCollection();
    }

    public static function fromEntity(Supply $supply): self
    {
        $model = new self();
        $model->setDescription($supply->getDescription());
        $model->setSupplyGroups(new ArrayCollection($supply->getSupplyGroups()->toArray()));

        return $model;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getSupplyGroups(): ArrayCollection
    {
        return $this->supplyGroups;
    }

    public function setSupplyGroups(ArrayCollection $supplyGroups): void
    {
        $this->supplyGroups = $supplyGroups;
    }
}
