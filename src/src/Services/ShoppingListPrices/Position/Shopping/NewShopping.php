<?php

namespace App\Services\ShoppingListPrices\Position\Shopping;

use App\Entity\Shop;
use App\Entity\Shopping;
use App\Entity\ShoppingList\Position;
use App\Entity\Unit;
use App\Services\ShoppingListPrices\Position\Dto\NewShopping as Dto;
use App\Services\ShoppingListPrices\Position\Shopping\Shopping as ShoppingInterface;
use App\Utils\UnitUtils;

class NewShopping implements ShoppingInterface
{
    private Unit $baseUnit;
    private Shopping $max;
    private Shopping $min;
    private Position $position;
    private Shop $shop;

    public function __construct(Shop $shop, Position $position, array $shopping)
    {
        $this->shop = $shop;
        $this->position = $position;
        usort($shopping, static fn(Shopping $a, Shopping $b): int => $a->getPrice() <=> $b->getPrice());
        $this->min = $shopping[0];
        $this->max = $shopping[count($shopping) - 1];
        if ($position->getUnit()->getMain() === null) {
            $this->baseUnit = $position->getUnit();
        } else {
            $this->baseUnit = $position->getUnit()->getMain();
        }
    }

    private function getPrice(float $amount, Unit $unit, Shopping $shopping): float
    {
        return UnitUtils::parseAmount($amount, $unit, $this->baseUnit) * $shopping->getPrice();
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
            $this->getPrice($this->position->getUnitValue(), $this->position->getUnit(), $this->min),
            $this->getPrice($this->position->getUnitValue(), $this->position->getUnit(), $this->max),
            $unit,
            $this->getPrice(1, $unit, $this->min),
            $this->getPrice(1, $unit, $this->max)
        );
    }
}
