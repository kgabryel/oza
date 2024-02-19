<?php

namespace App\Services\ShoppingListPrices;

use App\Config\Settings;
use App\Entity\Product;
use App\Entity\Shop;
use App\Entity\Shopping;
use App\Repository\ShoppingRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductsPricesService
{
    private int $newShoppingDays;
    private Product $product;
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
        TokenStorageInterface $tokenStorage
    ) {
        $this->shopping = [];
        $this->usedShops = [];
        $this->shoppingRepository = $shoppingRepository;
        $user = $tokenStorage->getToken()->getUser();
        $this->shops = $user->getShops()->toArray();
        $this->newShoppingDays = $session->get(Settings::NEW_SHOPPING_DAYS);
    }

    public function getShopping(): array
    {
        return $this->shopping;
    }

    public function setBestPrice(): self
    {
        $shopping = $this->shoppingRepository->getNewPricesForProducts(
            $this->product,
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
            $shopping = $this->shoppingRepository->getNewPricesForProducts(
                $this->product,
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
            $shopping = $this->shoppingRepository->getOldPricesForProduct(
                $this->product,
                $shop,
                $this->newShoppingDays
            );
            if ($shopping !== null) {
                $this->shopping[] = $shopping;
            }
        }

        return $this;
    }

    public function setProduct(Product $product): self
    {
        $this->shopping = [];
        $this->usedShops = [];
        $this->product = $product;

        return $this;
    }
}
