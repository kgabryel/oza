<?php

namespace App\Services\ShoppingListPrices\Position\Dto;

use App\Entity\Shop;
use JsonSerializable;

class NullShopping implements JsonSerializable, ShoppingInterface
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

    public function jsonSerialize(): array
    {
        return [
            'shopId' => $this->getShopId(),
            'shopName' => $this->getShopName()
        ];
    }

    public function getShopId(): int
    {
        return $this->shop->getId();
    }

    public function getShopName(): string
    {
        return $this->shop->getName();
    }
}
