<?php

namespace App\Services\Transformer;

use App\Entity\ShoppingList\Position;

class ShoppingListPositionTransformer
{
    public static function toArray(Position $position): array
    {
        $result = [
            'id' => $position->getId(),
            'unitId' => $position->getUnit()->getId(),
            'unitShortcut' => $position->getUnit()->getShortcut(),
            'amount' => $position->getUnitValue(),
            'checked' => $position->isChecked(),
            'description' => $position->getDescription()
        ];
        $product = $position->getProduct();
        if ($product === null) {
            $result['productId'] = $position->getGroup()
                ?->getId();
            $result['name'] = $position->getGroup()
                ?->getName();
            $result['isGroup'] = 1;
        } else {
            $result['productId'] = $product->getId();
            $result['name'] = (string)$product;
            $result['isGroup'] = 0;
        }
        $shop = $position->getShop();
        if ($shop !== null) {
            $result['shopId'] = $shop->getId();
            $result['shopName'] = $shop->getName();
        }

        return $result;
    }

    public static function toModelData(Position $position): array
    {
        return [
            'id' => $position->getId(),
            'type' => $position->getProduct() !== null ? 'product' : 'productsGroup',
            'position' => $position->getProduct() !== null ? $position->getProduct()->getId()
                : $position->getGroup()->getId(),
            'amount' => $position->getUnitValue(),
            'unit' => $position->getUnit(),
            'shop' => $position->getShop(),
            'checked' => $position->isChecked(),
            'description' => $position->getDescription()
        ];
    }
}
