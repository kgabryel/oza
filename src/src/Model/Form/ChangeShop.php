<?php

namespace App\Model\Form;

use App\Entity\Shop;

class ChangeShop
{
    private ?Shop $shop;

    public function __construct()
    {
        $this->shop = null;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(?Shop $shop): void
    {
        $this->shop = $shop;
    }
}
