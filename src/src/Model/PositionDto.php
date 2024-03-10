<?php

namespace App\Model;

use App\Entity\Product;
use App\Entity\ProductsGroup;
use App\Entity\Shopping;
use App\Entity\ShoppingList\Position;

class PositionDto
{
    private Position|Shopping $position;

    public function __construct(Position|Shopping $position)
    {
        $this->position = $position;
    }

    public function getProductsGroup(): ProductsGroup
    {
        return $this->position->getGroup();
    }

    public function getValue(): Product|ProductsGroup
    {
        return $this->position->getProduct() ?? $this->position->getGroup();
    }

    public function getProduct(): Product
    {
        return $this->position->getProduct();
    }

    public function isProduct(): bool
    {
        return $this->position->getProduct() !== null;
    }

    public function isProductsGroup(): bool
    {
        return $this->position->getGroup() !== null;
    }
}
