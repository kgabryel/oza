<?php

namespace App\Model\Form;

use App\Entity\Supply;
use App\Services\PositionFactory\PositionFactory;

class ShoppingPosition extends Position
{
    private bool $createSupply;
    private ?float $discount;
    private float $price;
    private ?Supply $supply;

    public function __construct(array $data, PositionFactory $factory)
    {
        parent::__construct($data, $factory);
        $this->createSupply = (bool)($data['createSupply'] ?? false);
        $this->supply = $data['supply'] ?? null;
        $this->price = (float)($data['price'] ?? 0);
        $this->discount = (float)($data['discount'] ?? 0);
    }

    public function createSupply(): bool
    {
        return $this->createSupply;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): void
    {
        $this->discount = $discount;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getSupply(): ?Supply
    {
        return $this->supply;
    }
}
