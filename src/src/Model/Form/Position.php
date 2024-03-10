<?php

namespace App\Model\Form;

use App\Entity\Product;
use App\Entity\ProductsGroup;
use App\Entity\Unit;
use App\Services\PositionFactory\PositionFactory;
use App\Utils\TypeUtils;
use App\Utils\UnitUtils;

abstract class Position
{
    protected ?Product $product;
    protected ?ProductsGroup $productsGroup;
    protected string $type;
    protected ?Unit $unit;
    protected float $amount;

    public function __construct(array $data, PositionFactory $factory)
    {
        $this->amount = (float)($data['amount'] ?? 0);
        $this->type = TypeUtils::getType($data['type']);
        $concreteFactory = $factory->get($this->type, (int)($data['position'] ?? 0));
        $this->productsGroup = $concreteFactory->getProductsGroup();
        $this->product = $concreteFactory->getProduct();

        if (($data['unit'] !== null)) {
            $unitChecked = UnitUtils::checkUnit(
                $this->productsGroup,
                $this->product,
                $data['unit']
            );
            if ($unitChecked) {
                $this->unit = $data['unit'];
            }
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

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getValue(): ProductsGroup|Product|null
    {
        return $this->product ?? $this->productsGroup;
    }
}
