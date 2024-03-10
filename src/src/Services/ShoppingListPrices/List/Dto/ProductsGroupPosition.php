<?php

namespace App\Services\ShoppingListPrices\List\Dto;

use App\Entity\ProductsGroup;
use App\Entity\ShoppingList\Position;

class ProductsGroupPosition
{
    private Position $position;

    public function __construct(Position $position)
    {
        $this->position = $position;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function getProductsGroup(): ProductsGroup
    {
        return $this->position->getValue()->getProductsGroup();
    }
}
