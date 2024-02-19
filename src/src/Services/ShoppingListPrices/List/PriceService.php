<?php

namespace App\Services\ShoppingListPrices\List;

use App\Config\Settings;
use App\Entity\Shop;
use App\Entity\Shopping;
use App\Entity\ShoppingList\Position;
use App\Entity\ShoppingList\ShoppingList;
use App\Services\ShoppingListPrices\List\Dto\ProductPosition;
use App\Services\ShoppingListPrices\List\Dto\ProductsGroupPosition;
use App\Services\ShoppingListPrices\List\Dto\Set;
use App\Services\ShoppingListPrices\List\Dto\Shopping as ShoppingDto;
use App\Services\ShoppingListPrices\ProductsGroupsPricesService;
use App\Services\ShoppingListPrices\ProductsPricesService;
use App\Utils\ShopsUtils;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PriceService
{
    /** @var ProductPosition[] */
    private array $products;
    /** @var ProductsGroupPosition[] */
    private array $productsGroups;
    private ProductsGroupsPricesService $productsGroupsPricesService;
    private ProductsPricesService $productsPricesService;
    private SessionInterface $session;
    /** @var Set[] */
    private array $sets;
    /** @var Shopping[] */
    private array $shopping;
    private ShoppingList $shoppingList;
    private array $shops;
    private array $shopsSets;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        SessionInterface $session,
        ProductsGroupsPricesService $productsGroupsPricesService,
        ProductsPricesService $productsPricesService
    ) {
        $user = $tokenStorage->getToken()->getUser();
        $this->session = $session;
        $this->shops = $user->getShops()->toArray();
        $this->shopsSets = [];
        $this->setShops();
        $this->shopping = [];
        $this->sets = [];
        $this->productsGroupsPricesService = $productsGroupsPricesService;
        $this->productsPricesService = $productsPricesService;
    }

    private function setShops(): void
    {
        $sets = [];
        for ($i = 1; $i <= $this->session->get(Settings::MAX_SHOPS_GROUP_COUNT); $i++) {
            $sets[] = ShopsUtils::getPowerSet($this->shops, $i);
        }
        $this->shopsSets = array_merge($this->shopsSets, ...$sets);
    }

    public function getSets(): array
    {
        return array_values(
            array_filter(
                $this->sets,
                fn(Set $set): bool => $set->isCorrect($this->shoppingList->getPositions()->count())
            )
        );
    }

    public function setSets(): self
    {
        foreach ($this->shopsSets as $shopsSet) {
            $shopping = array_filter(
                $this->shopping,
                static fn(Shopping $shopping): bool => in_array(
                    $shopping->getShop()->getId(),
                    array_map(static fn(Shop $shop) => $shop->getId(), $shopsSet),
                    true
                )
            );
            $this->sets[] = new Set(
                $shopsSet,
                array_map(fn(Shopping $shopping): ShoppingDto => new ShoppingDto(
                    $shopping->getShop(),
                    $this->getPositionForShopping($shopping),
                    $shopping
                ), $shopping)
            );
        }

        return $this;
    }

    private function getPositionForShopping(Shopping $shopping): Position
    {
        $group = $shopping->getGroup();
        if ($group !== null) {
            $filtered = array_values(
                array_filter(
                    $this->productsGroups,
                    static fn(ProductsGroupPosition $p): bool => $p->getProductsGroup()->getId() === $group->getId()
                )
            );

            return $filtered[0]->getPosition();
        }
        $filtered = array_values(
            array_filter(
                $this->products,
                static fn(ProductPosition $p): bool => $p->getProduct()->getId() === $shopping->getProduct()->getId()
            )
        );

        return $filtered[0]->getPosition();
    }

    public function setList(ShoppingList $shoppingList): self
    {
        $this->shoppingList = $shoppingList;
        $this->products = array_map(
            static fn(Position $position): ProductPosition => new ProductPosition($position),
            array_filter(
                $this->shoppingList->getPositions()->toArray(),
                static fn(Position $position): bool => $position->getProduct() !== null
            )
        );
        $this->productsGroups = array_map(
            static fn(Position $position): ProductsGroupPosition => new ProductsGroupPosition($position),
            array_filter(
                $this->shoppingList->getPositions()->toArray(),
                static fn(Position $position): bool => $position->getGroup() !== null
            )
        );

        return $this;
    }

    public function setPrices(): self
    {
        foreach ($this->products as $product) {
            $shopping = $this->productsPricesService->setProduct($product->getProduct())
                ->setNewPrices()
                ->setOldPrices()
                ->getShopping();
            foreach ($shopping as $position) {
                $this->shopping[] = $position;
            }
        }
        foreach ($this->productsGroups as $productsGroup) {
            $shopping = $this->productsGroupsPricesService->setProductsGroup($productsGroup->getProductsGroup())
                ->setNewPrices()
                ->setOldPrices()
                ->overrideProduct()
                ->getShopping();
            foreach ($shopping as $position) {
                $this->shopping[] = $position;
            }
        }

        return $this;
    }
}
