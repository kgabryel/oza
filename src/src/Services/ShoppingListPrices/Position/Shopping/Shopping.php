<?php

namespace App\Services\ShoppingListPrices\Position\Shopping;

use App\Entity\Shop;

interface Shopping
{
    public function getShop(): Shop;

    public function toDto(): mixed;
}
