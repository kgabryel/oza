<?php

namespace App\Services\Transformer;

use App\Entity\Shopping;
use Symfony\Component\Routing\RouterInterface;

class ShoppingTransformer
{
    public static function toFullArray(Shopping $shopping, RouterInterface $router): array
    {
        return [
            'id' => $shopping->getId(),
            'date' => $shopping->getDate()
                ?->format('Y-m-d'),
            'price' => $shopping->getPrice(),
            'originalPrice' => $shopping->getOriginalPrice(),
            'amount' => $shopping->getAmount(),
            'isGroup' => $shopping->isGroup(),
            'product' => [
                'id' => $shopping->getPosition()->getId(),
                'name' => (string)$shopping->getPosition()
            ],
            'shop' => [
                'url' => $router->generate('shops.show', [
                    'id' => $shopping->getShop()?->getId()
                ]),
                'name' => $shopping->getShop()?->getName()
            ],
            'unit' => [
                'id' => $shopping->getUnit()?->getId(),
                'name' => $shopping->getUnit()?->getName(),
                'shortcut' => $shopping->getUnit()?->getShortcut(),
                'mainId' => $shopping->getUnit()->getMain() === null
                    ? $shopping->getUnit()->getId()
                    : $shopping->getUnit()->getMain()?->getId(),
                'mainShortcut' => $shopping->getUnit()
                    ->getMain() === null
                    ? $shopping->getUnit()->getShortcut()
                    : $shopping->getUnit()->getMain()->getShortcut()
            ]
        ];
    }

    public static function toSimpleArray(Shopping $shopping): array
    {
        return [
            'date' => $shopping->getDate()
                ?->format('Y-m-d'),
            'price' => $shopping?->getPrice(),
            'isGroup' => $shopping->isGroup(),
            'product' => [
                'id' => $shopping->getPosition()->getId(),
                'name' => (string)$shopping->getPosition()
            ],
            'unit' => [
                'id' => $shopping->getUnit()
                    ->getMain() === null
                    ? $shopping->getUnit()->getId()
                    : $shopping->getUnit()->getMain()->getId(),
                'shortcut' => $shopping->getUnit()
                    ->getMain() === null
                    ? $shopping->getUnit()->getShortcut()
                    : $shopping->getUnit()->getMain()->getShortcut()
            ]
        ];
    }
}
