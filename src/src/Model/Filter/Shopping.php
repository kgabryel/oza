<?php

namespace App\Model\Filter;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

class Shopping
{
    private ?DateTime $dateFrom;
    private ?DateTime $dateTo;
    private ArrayCollection $products;
    private ArrayCollection $productsGroups;
    private ArrayCollection $shops;
    private ArrayCollection $units;

    public function __construct()
    {
        $this->dateFrom = null;
        $this->dateTo = null;
        $this->shops = new ArrayCollection();
        $this->units = new ArrayCollection();
        $this->productsGroups = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    public function getDateFrom(): ?string
    {
        return $this->dateFrom?->format('Y-m-d');
    }

    public function setDateFrom(?string $dateFrom): void
    {
        if ($dateFrom === null || !strtotime($dateFrom)) {
            $this->dateFrom = null;

            return;
        }
        $this->dateFrom = new DateTime($dateFrom);
    }

    public function getDateTo(): ?string
    {
        return $this->dateTo?->format('Y-m-d');
    }

    public function setDateTo(?string $dateTo): void
    {
        if ($dateTo === null || !strtotime($dateTo)) {
            $this->dateTo = null;

            return;
        }
        $this->dateTo = new DateTime($dateTo);
    }

    public function getProducts(): ArrayCollection
    {
        return $this->products;
    }

    public function setProducts(ArrayCollection $products): void
    {
        $this->products = $products;
    }

    public function getProductsGroups(): ArrayCollection
    {
        return $this->productsGroups;
    }

    public function setProductsGroups(ArrayCollection $productsGroups): void
    {
        $this->productsGroups = $productsGroups;
    }

    public function getShops(): ArrayCollection
    {
        return $this->shops;
    }

    public function setShops(ArrayCollection $shops): void
    {
        $this->shops = $shops;
    }

    public function getUnits(): ArrayCollection
    {
        return $this->units;
    }

    public function setUnits(ArrayCollection $units): void
    {
        $this->units = $units;
    }
}
