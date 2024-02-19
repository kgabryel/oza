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
        $clipboardPosition->setUser($this->user);
        $clipboardPosition->setProduct($position->getProduct());
        $clipboardPosition->setGroup($position->getGroup());
        $clipboardPosition->setUnit($position->getUnit());
        $clipboardPosition->setAmount($position->getUnitValue());
        $clipboardPosition->setDescription($position->getDescription());
        $this->entityManager->persist($clipboardPosition);
    }
}
