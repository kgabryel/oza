<?php

namespace App\Services\ShoppingListPrices\Position\Dto;

use App\Entity\Shop;
use App\Entity\Shopping;
use App\Entity\Unit;
use DateTimeInterface;

class OldShopping extends NullShopping
{
    private float $price;
    private Shopping $shopping;
    private float $singlePrice;
    private Unit $unit;

    public function __construct(Shop $shop, Shopping $shopping, float $price, Unit $unit, float $singlePrice)
    {
        parent::__construct($shop);
        $this->shopping = $shopping;
        $this->price = $price;
        $this->unit = $unit;
        $this->singlePrice = $singlePrice;
    }

    public function jsonSerialize(): array
    {
        return [
            'shopId' => $this->getShopId(),
            'shopName' => $this->getShopName(),
            'date' => $this->getDate(),
            'price' => $this->getPrice(),
            'shortcut' => $this->getShortcut(),
            'singlePrice' => $this->getSinglePrice()
        ];
    }

    public function getDate(): DateTimeInterface
    {
        return $this->shopping->getDate();
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getShortcut(): string
    {
        return $this->unit->getShortcut();
    }

    public function getSinglePrice(): float
    {
        return $this->singlePrice;
    }
}
