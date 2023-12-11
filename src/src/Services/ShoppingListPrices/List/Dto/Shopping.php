<?php

namespace App\Services\ShoppingListPrices\List\Dto;

use App\Entity\Shop;
use App\Entity\Shopping as ShoppingEntity;
use App\Entity\ShoppingList\Position;
use App\Entity\Unit;
use App\Utils\UnitUtils;

class Shopping
{
    private Position $position;
    private float $price;
    private Shop $shop;

    public function __construct(Shop $shop, Position $position, ShoppingEntity $shopping)
    {
        $this->shop = $shop;
        $this->position = $position;
        $this->setPrice($position->getUnitValue(), $position->getUnit(), $shopping);
    }

    private function setPrice(float $amount, Unit $unit, ShoppingEntity $shopping): void
    {
        $baseUnit = $this->position->getUnit()->getMain() ?? $this->position->getUnit();
        $this->price = UnitUtils::parseAmount($amount, $unit, $baseUnit) * $shopping->getPrice();
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getPositionId(): int
    {
        return $this->position->getId();
    }

    public function getShop(): Shop
    {
        return $this->shop;
    }
}
