<?php

namespace App\Utils;

use App\Model\Form\ShoppingPosition;

class ShoppingPositionUtils
{
    public static function getParsedPrice(float $price, ShoppingPosition $position): float
    {
        $parsedPrice = $price / $position->getAmount();
        if ($position->getUnit()->getMain() === null) {
            return $parsedPrice;
        }

        return $parsedPrice * $position->getUnit()->getConverter();
    }
}
