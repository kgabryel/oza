<?php

namespace App\Utils;

use App\Config\ProductPosition;

class TypeUtils
{
    public static function getType(?string $type): string
    {
        return in_array(
            $type,
            [
                ProductPosition::PRODUCTS_GROUP,
                ProductPosition::PRODUCT
            ],
            true
        ) ? $type : ProductPosition::PRODUCTS_GROUP;
    }
}
