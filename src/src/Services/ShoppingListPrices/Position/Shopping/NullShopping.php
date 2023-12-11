<?php

namespace App\Services\ShoppingListPrices\Position\Shopping;

use App\Entity\Shop;
use App\Services\ShoppingListPrices\Position\Dto\NullShopping as Dto;
use App\Services\ShoppingListPrices\Position\Shopping\Shopping as ShoppingInterface;

class NullShopping implements ShoppingInterface
{
    private Shop $shop;

    public function __construct(Shop $shop)
    {
        $this->shop = $shop;
    }

    public function getShop(): Shop
    {
        return $this->shop;
    }

    public function toDto(): Dto
    {
        return new Dto($this->shop);
    }
}
