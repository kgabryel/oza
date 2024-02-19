<?php

namespace App\Services\Chart;

use App\Config\Chart;
use App\Entity\ProductsGroup;

class ProductsGroupChartService extends ChartService
{
    private ProductsGroup $productsGroup;

    public function setProductsGroup(ProductsGroup $productsGroup): self
    {
        $this->productsGroup = $productsGroup;
        $this->data[Chart::UNIT] = $this->productsGroup->getUnit()->getShortcut();

        return $this;
    }

    protected function addPositions(): void
    {
        foreach ($this->productsGroup->getProducts() as $product) {
            $this->addProductPositions($product);
        }
        $this->addProductsGroupPositions($this->productsGroup);
    }
}
