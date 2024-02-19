<?php

namespace App\Services\Transformer;

use App\Entity\Product;
use App\Entity\ProductsGroup;

class ProductsGroupTransformer
{
    public static function toArray(ProductsGroup $productsGroup): array
    {
        $data = [
            'name' => $productsGroup->getName(),
            'id' => $productsGroup->getId(),
            'note' => $productsGroup->getNote(),
            'unit' => [
                'id' => $productsGroup->getUnit()->getId(),
                'name' => $productsGroup->getUnit()->getName()
            ],
            'baseUnit' => [
                'id' => $productsGroup->getBaseUnit()->getId(),
                'name' => $productsGroup->getBaseUnit()->getName()
            ],
            'products' => array_map(
                static fn(Product $product): array => [
                    'id' => $product->getId(),
                    'name' => (string)$product
                ],
                $productsGroup->getProducts()->toArray()
            ),
            'photo' => null
        ];
        $photo = $productsGroup->getMainPhoto();
        if ($photo !== null) {
            $data['photo'] = PhotoTransformer::toArray($photo);
        }
        return $data;
    }

    public static function toSupplyInfo(ProductsGroup $productsGroup): array
    {
        $supply = $productsGroup->getSupply();
        if ($supply === null) {
            return [
                'available' => false,
                'supplies' => []
            ];
        }
        return [
            'available' => true,
            'supplies' => [
                $supply->getId() => $productsGroup->getName()
            ]
        ];
    }
}
