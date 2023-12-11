<?php

namespace App\Services\ShoppingListPrices\List\Dto;

use JsonSerializable;

class Set implements JsonSerializable
{
    private array $positions;
    /** @var Shopping[] */
    private array $shopping;

    public function __construct(array $shops, array $shopping)
    {
        $this->shopping = $shopping;
        foreach ($shops as $shop) {
            $this->positions[$shop->getId()] = [];
        }
        $this->setPositions();
    }

    private function setPositions(): void
    {
        $positions = array_values(
            array_unique(
                array_map(static fn(Shopping $shopping): int => $shopping->getPositionId(), $this->shopping)
            )
        );
        foreach ($positions as $positionId) {
            $positionShopping = array_filter(
                $this->shopping,
                static fn(Shopping $shopping): bool => $shopping->getPositionId() === $positionId
            );
            usort($positionShopping, static fn(Shopping $a, Shopping $b): int => $a->getPrice() <=> $b->getPrice());
            $this->positions[$positionShopping[0]->getShop()->getId()][] = $positionShopping[0];
        }
    }

    public function isCorrect(int $positionsCount): bool
    {
        foreach ($this->positions as $position) {
            if (empty($position)) {
                return false;
            }
        }
        $count = 0;
        foreach ($this->positions as $position) {
            $count += count($position);
        }

        return $count === $positionsCount;
    }

    public function jsonSerialize(): array
    {
        $filtered = array_filter($this->positions, static fn(array $positions): bool => count($positions) > 0);
        $sets = [];
        foreach ($filtered as $positions) {
            $shop = $positions[0]->getShop();
            $sets[] = [
                'shopId' => $shop->getId(),
                'shopName' => $shop->getName(),
                'price' => array_sum(
                    array_map(static fn(Shopping $shopping): float => $shopping->getPrice(), $positions)
                ),
                'positions' => array_map(static fn(Shopping $shopping): int => $shopping->getPositionId(), $positions)
            ];
        }

        return [
            'shops' => $sets,
            'totalPrice' => array_sum(array_map(static fn(array $set): float => $set['price'], $sets))
        ];
    }
}
