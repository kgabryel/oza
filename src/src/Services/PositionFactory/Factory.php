<?php

namespace App\Services\PositionFactory;

use App\Entity\Product;
use App\Entity\ProductsGroup;

interface Factory
{
    public function getProduct(): ?Product;

    public function getProductsGroup(): ?ProductsGroup;
}
