<?php

namespace App\Services\Chart;

use App\Config\Chart;
use App\Entity\Product;
use App\Entity\ProductsGroup;
use App\Services\Chart\Dto\Position;
use App\Services\Chart\Dto\Shopping;
use App\Utils\ChartUtils;
use Ramsey\Uuid\Uuid;

abstract class ChartService
{
    protected array $data;
    protected array $groupsNames;
    protected array $groupsPositions;
    protected array $productsNames;
    protected array $productsPositions;

    public function __construct()
    {
        $this->data = [
            Chart::SHOPPING => []
        ];
        $this->productsNames = [];
        $this->productsPositions = [];
        $this->groupsNames = [];
        $this->groupsPositions = [];
    }

    public function fillData(): self
    {
        $this->addPositions();
        foreach ($this->groupsNames as $id => $name) {
            if (empty($this->groupsPositions[$id])) {
                continue;
            }
            $this->data[Chart::SHOPPING][] = new Shopping($name, $this->groupsPositions[$id]);
        }
        foreach ($this->productsNames as $id => $name) {
            if (empty($this->productsPositions[$id])) {
                continue;
            }
            $this->data[Chart::SHOPPING][] = new Shopping($name, $this->productsPositions[$id]);
        }
        $this->clearPositions();

        return $this;
    }

    abstract protected function addPositions(): void;

    public function clearPositions(): void
    {
        foreach ($this->data['shopping'] as $key => $data) {
            $this->data['shopping'][$key]->setPositions(
                array_values(
                    array_unique(
                        array_map(static fn(Position $position) => $position->toArray(), $data->getPositions()),
                        SORT_REGULAR
                    )
                )
            );
        }
    }

    public function getData(): array
    {
        return $this->data;
    }

    protected function addProductPositions(Product $product): void
    {
        $id = Uuid::uuid1()->toString();
        $this->productsNames[$id] = (string)$product;
        $this->productsPositions[$id] = [];
        foreach ($product->getShopping() as $shopping) {
            $this->productsPositions[$id][] = new Position($shopping);
            $positionName = ChartUtils::getPositionName((string)$product, $shopping->getShop());
            $shopId = array_search($positionName, $this->productsNames, true);
            if ($shopId === false) {
                $shopId = Uuid::uuid1()->toString();
                $this->productsNames[$shopId] = $positionName;
            }
            $this->productsPositions[$shopId][] = new Position($shopping);
        }
    }

    protected function addProductsGroupPositions(ProductsGroup $productsGroup): void
    {
        $id = Uuid::uuid1()->toString();
        $this->groupsNames[$id] = $productsGroup->getName();
        $this->groupsPositions[$id] = [];
        foreach ($productsGroup->getShopping() as $shopping) {
            $this->groupsPositions[$id][] = new Position($shopping);
            $positionName = ChartUtils::getPositionName($productsGroup->getName(), $shopping->getShop());
            $shopId = array_search($positionName, $this->groupsNames, true);
            if ($shopId === false) {
                $shopId = Uuid::uuid1()->toString();
                $this->groupsNames[$shopId] = $positionName;
            }
            $this->groupsPositions[$shopId][] = new Position($shopping);
        }
    }
}
