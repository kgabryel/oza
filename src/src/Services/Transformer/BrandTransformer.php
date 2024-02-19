<?php

namespace App\Services\Transformer;

use App\Entity\Brand;
use App\Entity\Product;

class BrandTransformer
{
    public static function toArray(Brand $brand): array
    {
        return [
            'id' => $brand->getId(),
            'name' => $brand->getName(),
            'description' => $brand->getDescription(),
            'products' => array_map(
                static fn(Product $product): array => [
                    'id' => $product->getId(),
                    'name' => $product->getName()
                ],
                $brand->getProducts()->toArray()
            )
        ];
    }
}
