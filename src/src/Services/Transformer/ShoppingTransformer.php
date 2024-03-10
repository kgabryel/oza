<?php

namespace App\Services\Transformer;

use App\Entity\Shopping;
use Symfony\Component\Routing\RouterInterface;

class ShoppingTransformer
{
    public static function toFullArray(Shopping $shopping, RouterInterface $router): array
    {
        $shop = $shopping->getShop();
        $unit = $shopping->getUnit();
        $position = $shopping->getPosition()->getValue();

        return [
            'id' => $shopping->getId(),
            'date' => $shopping->getDate()->format('Y-m-d'),
            'price' => $shopping->getPrice(),
            'originalPrice' => $shopping->getOriginalPrice(),
            'amount' => $shopping->getAmount(),
            'isGroup' => $shopping->getPosition()->isProductsGroup(),
            'product' => [
                'id' => $position->getId(),
                'name' => (string)$position
            ],
            'shop' => [
                'url' => $router->generate('shops.show', [
                    'id' => $shop->getId()
                ]),
                'name' => $shop->getName()
            ],
            'unit' => [
                'id' => $unit->getId(),
                'name' => $unit->getName(),
                'shortcut' => $unit->getShortcut(),
                'mainId' => $unit->getMain() === null ? $unit->getId() : $unit->getMain()->getId(),
                'mainShortcut' => $unit->getMain() === null ? $unit->getShortcut() : $unit->getMain()->getShortcut()
            ]
        ];
    }

    public static function toSimpleArray(Shopping $shopping): array
    {
        $unit = $shopping->getUnit();
        $position = $shopping->getPosition()->getValue();

        return [
            'date' => $shopping->getDate()->format('Y-m-d'),
            'price' => $shopping->getPrice(),
            'isGroup' => $shopping->getPosition()->isProductsGroup(),
            'product' => [
                'id' => $position->getId(),
                'name' => (string)$position
            ],
            'unit' => [
                'id' => $unit->getMain() === null ? $unit->getId() : $unit->getMain()->getId(),
                'shortcut' => $unit->getMain() === null ? $unit->getShortcut() : $unit->getMain()->getShortcut()
            ]
        ];
    }
}
