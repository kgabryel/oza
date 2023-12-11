<?php

namespace App\Model\Form;

use App\Entity\Product;
use App\Entity\ProductsGroup;
use App\Entity\Supply;
use App\Entity\Unit;
use App\Services\PositionFactory\PositionFactory;
use App\Utils\TypeUtils;
use App\Utils\UnitUtils;

class ShoppingPosition
{
    private float $amount;
    private bool $createSupply;
    private ?float $discount;
    private float $price;
    private ?Product $product;
    private ?ProductsGroup $productsGroup;
    private ?Supply $supply;
    private string $type;
    private ?Unit $unit;

    public function __construct(array $data, PositionFactory $factory)
    {
        $this->createSupply = (bool)($data['createSupply'] ?? false);
        $this->supply = $data['supply'] ?? null;
        $this->amount = (float)($data['amount'] ?? 0);
        $this->price = (float)($data['price'] ?? 0);
        $this->discount = (float)($data['discount'] ?? 0);
        $this->type = TypeUtils::getType($data['type']);
        $concreteFactory = $factory->get($this->type, (int)($data['position'] ?? 0));
        $this->productsGroup = $concreteFactory->getProductsGroup();
        $this->product = $concreteFactory->getProduct();
        if (
            ($data['unit'] !== null)
            && UnitUtils::checkUnit(
                $this->productsGroup,
                $this->product,
                $data['unit']
            )
        ) {
            $this->unit = $data['unit'];
        } else {
            $this->unit = null;
        }
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getProductsGroup(): ?ProductsGroup
    {
        return $this->productsGroup;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function createSupply(): bool
    {
        return $this->createSupply;
    }

    public function getAmount(): float
    {
        return $this->amount;
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

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }
}
