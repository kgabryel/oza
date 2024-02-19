<?php

namespace App\Services;

use App\Config\ProductPosition;
use App\Model\Form\ShoppingListPosition;
use App\Utils\UnitUtils;

class PositionMergeService
{
    private array $indexes;
    /**
     * @var ShoppingListPosition[]
     */
    private array $positions;
    private array $toMerge;

    /**
     * @return ShoppingListPosition[]
     */
    public function getPositions(): array
    {
        return $this->positions;
    }

    /**
     * @param ShoppingListPosition[] $positions
     *
     * @return $this
     */
    public function setPositions(array $positions): self
    {
        $this->positions = $positions;
        $this->indexes = [];
        $this->toMerge = [];
        $this->setIndexes();

        return $this;
    }

    private function setIndexes(): void
    {
        foreach ($this->positions as $key => $position) {
            if ($position->getType() === ProductPosition::PRODUCTS_GROUP) {
                $index = sprintf('g_%s', $position->getProductsGroup()->getId());
            } else {
                $index = sprintf('p_%s', $position->getProduct()->getId());
            }
            if (!isset($this->indexes[$index])) {
                $this->indexes[$index] = $key;
            } else {
                $this->toMerge[] = [
                    'to' => $this->indexes[$index],
                    'from' => $key
                ];
            }
        }
    }

    public function merge(): self
    {
        foreach ($this->toMerge as $item) {
            $greaterUnit = UnitUtils::findGreaterUnit(
                $this->positions[$item['from']]->getUnit(),
                $this->positions[$item['to']]->getUnit()
            );
            $amount1 = UnitUtils::parseAmount(
                $this->positions[$item['from']]->getAmount(),
                $this->positions[$item['from']]->getUnit(),
                $greaterUnit
            );
            $amount2 = UnitUtils::parseAmount(
                $this->positions[$item['to']]->getAmount(),
                $this->positions[$item['to']]->getUnit(),
                $greaterUnit
            );
            $this->positions[$item['to']]->setUnit($greaterUnit);
            $this->positions[$item['to']]->setAmount($amount1 + $amount2);
            unset($this->positions[$item['from']]);
        }

        return $this;
    }
}
