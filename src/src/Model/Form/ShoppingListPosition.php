<?php

namespace App\Model\Form;

use App\Entity\Product;
use App\Entity\ProductsGroup;
use App\Entity\Shop;
use App\Entity\Unit;
use App\Services\PositionFactory\PositionFactory;
use App\Utils\TypeUtils;
use App\Utils\UnitUtils;

class ShoppingListPosition
{
    private float $amount;
    private bool $checked;
    private ?string $description;
    private ?Product $product;
    private ?ProductsGroup $productsGroup;
    private ?Shop $shop;
    private string $type;
    private ?Unit $unit;

    public function __construct(array $data, PositionFactory $factory)
    {
        $this->checked = (bool)($data['checked'] ?? false);
        $this->description = $data['description'];
        $this->shop = $data['shop'];
        $this->amount = (float)($data['amount'] ?? 0);
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

    public function getType(): ?string
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

    public function getAmount(): float
    {
        return $this->amount;
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
        if ($this->productsGroup === null) {
            return $this->product->getId();
        }

        return $this->productsGroup->getId();
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
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
