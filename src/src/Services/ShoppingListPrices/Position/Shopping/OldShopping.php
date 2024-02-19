<?php

namespace App\Services\ShoppingListPrices\Position\Shopping;

use App\Entity\Shop;
use App\Entity\Shopping;
use App\Entity\ShoppingList\Position;
use App\Entity\Unit;
use App\Services\ShoppingListPrices\Position\Dto\OldShopping as Dto;
use App\Services\ShoppingListPrices\Position\Shopping\Shopping as ShoppingInterface;
use App\Utils\UnitUtils;

class OldShopping implements ShoppingInterface
{
    private Unit $baseUnit;
    private Position $position;
    private Shop $shop;
    private Shopping $shopping;

    public function __construct(Shop $shop, Position $position, Shopping $shopping)
    {
        $this->shop = $shop;
        $this->position = $position;
        $this->shopping = $shopping;
        if ($this->position->getUnit()->getMain() === null) {
            $this->baseUnit = $this->position->getUnit();
        } else {
            $this->baseUnit = $this->position->getUnit()->getMain();
        }
    }

    private function getUnit(): Unit
    {
        $group = $this->position->getGroup();
        if ($group !== null) {
            return $group->getBaseUnit();
        }

        return $this->position->getProduct()->getUnit();
    }

    public function getShop(): Shop
    {
        return $this->shop;
    }

    public function toDto(): Dto
    {
        $unit = $this->getUnit();

        return new Dto(
            $this->shop,
            $this->shopping,
            $this->getPrice($this->position->getUnitValue(), $this->position->getUnit(), $this->shopping),
            $unit,
            $this->getPrice(1, $unit, $this->shopping)
        );
    }

    private function getPrice(float $amount, Unit $unit, Shopping $shopping): float
    {
        return UnitUtils::parseAmount($amount, $unit, $this->baseUnit) * $shopping->getPrice();
    }
}
