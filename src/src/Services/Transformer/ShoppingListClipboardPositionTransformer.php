<?php

namespace App\Services\Transformer;

use App\Entity\ShoppingList\ClipboardPosition;

class ShoppingListClipboardPositionTransformer
{
    public static function toArray(ClipboardPosition $clipboardPosition): array
    {
        $result = [
            'id' => $clipboardPosition->getId(),
            'unitId' => $clipboardPosition->getUnit()->getId(),
            'unitShortcut' => $clipboardPosition->getUnit()->getShortcut(),
            'amount' => $clipboardPosition->getAmount(),
            'description' => $clipboardPosition->getDescription()
        ];
        $product = $clipboardPosition->getProduct();
        if ($product === null) {
            $result['productId'] = $clipboardPosition->getGroup()
                ?->getId();
            $result['productName'] = $clipboardPosition->getGroup()
                ?->getName();
            $result['isGroup'] = 1;
        } else {
            $result['productId'] = $product->getId();
            $result['productName'] = (string)$product;
            $result['isGroup'] = 0;
        }

        return $result;
    }
}
