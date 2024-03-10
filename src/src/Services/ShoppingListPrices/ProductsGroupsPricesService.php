<?php

namespace App\Services\ShoppingListPrices;

use App\Config\Settings;
use App\Entity\ProductsGroup;
use App\Entity\Shop;
use App\Entity\Shopping;
use App\Repository\ShoppingRepository;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProductsGroupsPricesService
{
    private int $newShoppingDays;
    private ProductsGroup $productsGroup;
    /** @var Shopping[] */
    private array $shopping;
    private ShoppingRepository $shoppingRepository;
    /** @var Shop[] */
    private array $shops;
    /** @var int[] */
    private array $usedShops;

    public function __construct(
        SessionInterface $session,
        ShoppingRepository $shoppingRepository,
        UserService $userService
    ) {
        $this->shopping = [];
        $this->usedShops = [];
        $this->shoppingRepository = $shoppingRepository;
        $user = $userService->getUser();
        $this->shops = $user->getShops()->toArray();
        $this->newShoppingDays = $session->get(Settings::NEW_SHOPPING_DAYS);
    }

    public function getShopping(): array
    {
        return $this->shopping;
    }

    public function overrideProduct(): self
    {
        foreach ($this->shopping as $shopping) {
            $shopping->setProduct(null);
            $shopping->setGroup($this->productsGroup);
        }

        return $this;
    }

    public function setBestPrice(): self
    {
        $shopping = $this->shoppingRepository->getNewPricesForProductsGroup(
            $this->productsGroup,
            null,
            $this->newShoppingDays
        )[0] ?? null;
        if ($shopping !== null) {
            $this->shopping[] = $shopping;
        }

        return $this;
    }

    public function setNewPrices(): self
    {
        foreach ($this->shops as $shop) {
            $shopping = $this->shoppingRepository->getNewPricesForProductsGroup(
                $this->productsGroup,
                $shop,
                $this->newShoppingDays
            )[0] ?? null;
            if ($shopping !== null) {
                $this->usedShops[] = $shop->getId();
                $this->shopping[] = $shopping;
            }
        }

        return $this;
    }

    public function setOldPrices(): self
    {
        $shopsWithoutShopping = array_filter(
            $this->shops,
            fn(Shop $shop): bool => !in_array($shop->getId(), $this->usedShops, true)
        );

        foreach ($shopsWithoutShopping as $shop) {
            $shopping = $this->shoppingRepository->getOldPricesForProductsGroup(
                $this->productsGroup,
                $shop,
                $this->newShoppingDays
            );
            if ($shopping !== null) {
                $this->shopping[] = $shopping;
            }
        }

        return $this;
    }

    public function setProductsGroup(ProductsGroup $productsGroup): self
    {
        $this->shopping = [];
        $this->usedShops = [];
        $this->productsGroup = $productsGroup;

        return $this;
    }
}
