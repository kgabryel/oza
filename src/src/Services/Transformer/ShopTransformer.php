<?php

namespace App\Services\Transformer;

use App\Entity\Shop;

class ShopTransformer
{
    public static function toArray(Shop $shop): array
    {
        return [
            'id' => $shop->getId(),
            'name' => $shop->getName(),
            'description' => $shop->getDescription()
        ];
    }
}
