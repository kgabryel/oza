<?php

namespace App\Utils;

use App\Entity\Shop;

class ChartUtils
{
    public static function getPositionName(string $positionName, Shop $shop): string
    {
        return sprintf('%s (%s)', $positionName, $shop->getName());
    }
}
