<?php

namespace App\Services\ShoppingListPrices\List\Dto;

use App\Entity\Product;
use App\Entity\ShoppingList\Position;

class ProductPosition
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

    public function getProduct(): Product
    {
        return $this->position->getValue()->getProduct();
    }
}
