<?php

namespace App\Services\ShoppingListPrices\Position\Dto;

use App\Entity\Shop;
use App\Entity\Unit;

class NewShopping extends NullShopping
{
    private float $max;
    private float $min;
    private float $singleMaxPrice;
    private float $singleMinPrice;
    private Unit $unit;

    public function __construct(
        Shop $shop,
        float $min,
        float $max,
        Unit $unit,
        float $singleMinPrice,
        float $singleMaxPrice
    ) {
        parent::__construct($shop);
        $this->min = $min;
        $this->max = $max;
        $this->unit = $unit;
        $this->singleMinPrice = $singleMinPrice;
        $this->singleMaxPrice = $singleMaxPrice;
    }

    public function jsonSerialize(): array
    {
        return [
            'shopId' => $this->getShopId(),
            'shopName' => $this->getShopName(),
            'min' => $this->getMin(),
            'max' => $this->getMax(),
            'shortcut' => $this->getShortcut(),
            'singleMinPrice' => $this->getSingleMinPrice(),
            'singleMaxPrice' => $this->getSingleMaxPrice()
        ];
    }

    public function getMin(): float
    {
        return $this->min;
    }

    public function getMax(): float
    {
        return $this->max;
    }

    public function getShortcut(): string
    {
        return $this->unit->getShortcut();
    }

    public function getSingleMinPrice(): float
    {
        return $this->singleMinPrice;
    }

    public function getSingleMaxPrice(): float
    {
        return $this->singleMaxPrice;
    }
}
