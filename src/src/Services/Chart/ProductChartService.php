<?php

namespace App\Services\Chart;

use App\Config\Chart;
use App\Entity\Product;

class ProductChartService extends ChartService
{
    private Product $product;

    public function setProduct(Product $product): self
    {
        $this->product = $product;
        $unit = $this->product->getUnit();
        if ($unit->getMain() !== null) {
            $unit = $unit->getMain();
        }
        $this->data[Chart::UNIT] = $unit->getShortcut();

        return $this;
    }

    protected function addPositions(): void
    {
        $this->addProductPositions($this->product);
        foreach ($this->product->getGroups() as $group) {
            $this->addProductsGroupPositions($group);
        }
    }
}
