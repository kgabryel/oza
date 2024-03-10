<?php

namespace App\Services\Transformer;

use App\Entity\Product;
use App\Entity\ProductsGroup;

class ProductTransformer
{
    public static function toArray(Product $product): array
    {
        $data = [
            'id' => $product->getId(),
            'defaultAmount' => $product->getDefaultAmount(),
            'name' => $product->getName(),
            'productsGroups' => array_map(
                static fn(ProductsGroup $productsGroup): array => [
                    'id' => $productsGroup->getId(),
                    'name' => $productsGroup->getName()
                ],
                $product->getGroups()->toArray()
            ),
            'unit' => [
                'id' => $product->getUnit()->getId(),
                'name' => $product->getUnit()->getName()
            ],
            'note' => $product->getNote(),
            'brand' => null,
            'photo' => null
        ];
        $brand = $product->getBrand();
        if ($brand !== null) {
            $data['brand'] = [
                'id' => $brand->getId(),
                'name' => $brand->getName()
            ];
        }
        $photo = $product->getMainPhoto();
        if ($photo !== null) {
            $data['photo'] = PhotoTransformer::toArray($photo);
        }

        return $data;
    }

    public static function toSupplyInfo(Product $product): array
    {
        $groups = [];
        foreach ($product->getGroups() as $group) {
            $supply = $group->getSupply();
            if ($supply !== null) {
                $groups[$supply->getId()] = $group->getName();
            }
        }

        if ($groups === []) {
            return [
                'available' => false,
                'supplies' => []
            ];
        }

        return [
            'available' => true,
            'supplies' => $groups
        ];
    }
}
