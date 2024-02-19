<?php

namespace App\Model\Form;

use App\Entity\Shop;

class Shopping
{
    private ?string $date;
    /** @var ShoppingPosition[] */
    private array $positions;
    private ?Shop $shop;

    public function __construct()
    {
        $this->shop = null;
        $this->date = null;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): void
    {
        $this->date = $date;
    }

    public function getPositions(): array
    {
        return $this->positions;
    }

    public function setPositions(array $positions): void
    {
        $this->positions = $positions;
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
