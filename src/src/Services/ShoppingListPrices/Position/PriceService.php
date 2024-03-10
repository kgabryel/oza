<?php

namespace App\Services\ShoppingListPrices\Position;

use App\Config\Settings;
use App\Entity\Shop;
use App\Entity\Shopping;
use App\Entity\ShoppingList\Position;
use App\Entity\User;
use App\Repository\ShoppingRepository;
use App\Services\ShoppingListPrices\Position\Dto\NewShopping as NewShoppingDto;
use App\Services\ShoppingListPrices\Position\Dto\NullShopping as NullShoppingDto;
use App\Services\ShoppingListPrices\Position\Dto\OldShopping as OldShoppingDto;
use App\Services\ShoppingListPrices\Position\Shopping\NewShopping;
use App\Services\ShoppingListPrices\Position\Shopping\NullShopping;
use App\Services\ShoppingListPrices\Position\Shopping\OldShopping;
use App\Services\ShoppingListPrices\Position\Shopping\Shopping as ShoppingInterface;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PriceService
{
    private int $newShoppingDays;
    private Position $position;
    private ShoppingRepository $shoppingRepository;
    private array $shops;
    private User $user;

    public function __construct(
        UserService $userService,
        ShoppingRepository $shoppingRepository,
        SessionInterface $session
    ) {
        $this->user = $userService->getUser();
        $this->setShops();
        $this->shoppingRepository = $shoppingRepository;
        $this->newShoppingDays = $session->get(Settings::NEW_SHOPPING_DAYS);
    }

    private function setShops(): void
    {
        $this->shops = array_map(
            static fn(Shop $shop): NullShopping => new NullShopping($shop),
            $this->user->getShops()->toArray()
        );
    }

    public function getShops(): array
    {
        $shops = array_map(
            static fn(ShoppingInterface $shopping): Dto\ShoppingInterface => $shopping->toDto(),
            $this->shops
        );
        $sorted = $this->sortNewShopping(
            array_filter($shops, static fn($shopping): bool => $shopping instanceof NewShoppingDto)
        );
        $sorted = array_merge(
            $sorted,
            $this->sortOldShopping(
                array_filter($shops, static fn($shopping): bool => $shopping instanceof OldShoppingDto)
            )
        );

        return array_merge(
            $sorted,
            array_filter($shops, static fn($shopping): bool => $shopping instanceof NullShoppingDto)
        );
    }

    private function sortNewShopping(array $shopping): array
    {
        usort($shopping, static fn(NewShoppingDto $a, NewShoppingDto $b): int => $a->getMin() <=> $b->getMin());

        return $shopping;
    }

    private function sortOldShopping(array $shopping): array
    {
        usort($shopping, static fn(OldShoppingDto $a, OldShoppingDto $b): int => $a->getDate() <=> $b->getDate());

        return $shopping;
    }

    public function findPrices(): void
    {
        $newPrices = $this->getNewPrices();
        /** @var NullShopping $value */
        foreach ($this->shops as $key => $value) {
            $prices = array_filter(
                $newPrices,
                static fn(Shopping $shopping): bool => $shopping->getShop()->getId() === $value->getShop()->getId()
            );
            if ($prices === []) {
                continue;
            }
            $this->shops[$key] = new NewShopping($value->getShop(), $this->position, $prices);
        }
        foreach ($this->shops as $key => $value) {
            if ($value instanceof NewShopping) {
                continue;
            }
            $shopping = $this->getOldShopping($value->getShop());
            if ($shopping === null) {
                continue;
            }
            $this->shops[$key] = new OldShopping($value->getShop(), $this->position, $shopping);
        }
    }

    private function getNewPrices(): array
    {
        $positionValue = $this->position->getValue();
        if ($positionValue->isProductsGroup()) {
            return $this->shoppingRepository->getNewPricesForProductsGroup(
                $positionValue->getProductsGroup(),
                null,
                $this->newShoppingDays,
                null
            );
        }

        return $this->shoppingRepository->getNewPricesForProducts(
            $positionValue->getProduct(),
            null,
            $this->newShoppingDays,
            null
        );
    }

    private function getOldShopping(Shop $shop): ?Shopping
    {
        $positionValue = $this->position->getValue();
        if ($positionValue->isProductsGroup()) {
            return $this->shoppingRepository->getOldPricesForProductsGroup(
                $positionValue->getProductsGroup(),
                $shop,
                $this->newShoppingDays
            );
        }

        return $this->shoppingRepository->getOldPricesForProduct(
            $positionValue->getProduct(),
            $shop,
            $this->newShoppingDays
        );
    }

    public function setPosition(Position $position): self
    {
        $this->position = $position;
        $this->setShops();

        return $this;
    }
}
