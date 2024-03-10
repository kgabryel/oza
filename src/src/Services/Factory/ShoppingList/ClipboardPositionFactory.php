<?php

namespace App\Services\Factory\ShoppingList;

use App\Entity\ShoppingList\ClipboardPosition;
use App\Entity\ShoppingList\Position;
use App\Services\Factory\EntityFactory;

class ClipboardPositionFactory extends EntityFactory
{
    public function create(Position $position): void
    {
        $clipboardPosition = new ClipboardPosition();
        $clipboardPosition->setUser($this->user)
            ->setProduct($position->getProduct())
            ->setGroup($position->getGroup())
            ->setUnit($position->getUnit())
            ->setAmount($position->getUnitValue())
            ->setDescription($position->getDescription());
        $this->entityManager->persist($clipboardPosition);
    }
}
