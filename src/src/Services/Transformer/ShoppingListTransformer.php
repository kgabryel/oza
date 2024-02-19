<?php

namespace App\Services\Transformer;

use App\Entity\ShoppingList\Position;
use App\Entity\ShoppingList\ShoppingList;

class ShoppingListTransformer
{
    public static function toArray(ShoppingList $shoppingList): array
    {
        $positions = [];
        /** @var Position $position */
        foreach ($shoppingList->getPositions() as $position) {
            $product = [
                'id' => $position->getId(),
                'unitId' => $position->getUnit()->getId(),
                'unitShortcut' => $position->getUnit()->getShortcut(),
                'amount' => $position->getUnitValue(),
                'checked' => $position->isChecked(),
                'description' => $position->getDescription(),
                'photo' => null
            ];
            $productsGroup = $position->getGroup();
            if ($productsGroup === null) {
                $product['productId'] = $position->getProduct()?->getId();
                $product['name'] = (string)$position->getProduct();
                $product['isGroup'] = 0;
                $photo = $position->getProduct()?->getMainPhoto();
            } else {
                $product['productId'] = $productsGroup->getId();
                $product['name'] = (string)$productsGroup;
                $product['isGroup'] = 1;
                $photo = $productsGroup->getMainPhoto();
            }
            if ($photo !== null) {
                $product['photo'] = PhotoTransformer::toArray($photo);
            }
            $shop = $position->getShop();
            if ($shop !== null) {
                $product['shopId'] = $shop->getId();
                $product['shopName'] = $shop->getName();
            }
            $positions[] = $product;
        }

        return [
            'id' => $shoppingList->getId(),
            'name' => $shoppingList->getName(),
            'note' => $shoppingList->getNote(),
            'createdAt' => $shoppingList->getCreatedAt()->format('Y-m-d H:i'),
            'positions' => $positions
        ];
    }
}
