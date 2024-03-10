<?php

namespace App\Model\Form;

use App\Entity\Product;
use App\Entity\ProductsGroup;
use App\Entity\Shop;
use App\Entity\Unit;
use App\Services\PositionFactory\PositionFactory;

class ShoppingListPosition extends Position
{
    private bool $checked;
    private ?string $description;
    private ?Shop $shop;

    public function __construct(array $data, PositionFactory $factory)
    {
        parent::__construct($data, $factory);
        $this->checked = (bool)($data['checked'] ?? false);
        $this->description = $data['description'];
        $this->shop = $data['shop'];
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(mixed $description): void
    {
        $this->description = $description;
    }

    public function getPosition(): int
    {
        /** @var ProductsGroup|Product $position */
        $position = $this->productsGroup ?? $this->product;

        return $position->getId();
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setUnit(?Unit $unit): void
    {
        $this->unit = $unit;
    }

    public function isChecked(): bool
    {
        return $this->checked;
    }
}
