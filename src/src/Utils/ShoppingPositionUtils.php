<?php

namespace App\Utils;

use App\Entity\Unit;
use App\Model\Form\ShoppingPosition;

class ShoppingPositionUtils
{
    public static function getParsedPrice(float $price, ShoppingPosition $position): float
    {
        /** @var Unit $unit */
        $unit = $position->getUnit();
        $parsedPrice = $price / $position->getAmount();
        if ($unit->getMain() === null) {
            return $parsedPrice;
        }

        return $parsedPrice * $unit->getConverter();
    }
}
