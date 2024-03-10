<?php

namespace App\Services\Factory\ShoppingList;

use App\Entity\Shop;
use App\Entity\ShoppingList\Position;
use App\Model\Form\ShoppingListPosition;
use App\Services\ShoppingListPrices\ProductsGroupsPricesService;
use App\Services\ShoppingListPrices\ProductsPricesService;

class PositionFactory
{
    private Position $position;
    private ProductsGroupsPricesService $productsGroupsPricesService;
    private ProductsPricesService $productsPricesService;

    public function __construct(
        ProductsPricesService $productsPricesService,
        ProductsGroupsPricesService $productsGroupsPricesService
    ) {
        $this->productsPricesService = $productsPricesService;
        $this->productsGroupsPricesService = $productsGroupsPricesService;
    }

    public function create(ShoppingListPosition $data): self
    {
        $this->position = new Position();
        $this->position->setUnitValue($data->getAmount())
            ->setUnit($data->getUnit())
            ->setProduct($data->getProduct())
            ->setGroup($data->getProductsGroup())
            ->setShop($data->getShop() ?? $this->findShop());
        $data->isChecked() ? $this->position->check() : $this->position->unCheck();
        if ($data->getDescription() !== null && $data->getDescription() !== '') {
            $this->position->setDescription($data->getDescription());
        }

        return $this;
    }

    private function findShop(): ?Shop
    {
        if ($this->position->getProduct() === null) {
            return $this->findShopForProductsGroups();
        }

        return $this->findShopForProduct();
    }

    private function findShopForProductsGroups(): ?Shop
    {
        $shopping = $this->productsGroupsPricesService->setProductsGroup($this->position->getGroup())
            ->setBestPrice()
            ->getShopping();
        if ($shopping === []) {
            return null;
        }

        return $shopping[0]->getShop();
    }

    private function findShopForProduct(): ?Shop
    {
        $shopping = $this->productsPricesService->setProduct($this->position->getProduct())
            ->setBestPrice()
            ->getShopping();
        if ($shopping === []) {
            return null;
        }

        return $shopping[0]->getShop();
    }

    public function get(): Position
    {
        return $this->position;
    }
}
