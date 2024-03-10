<?php

namespace App\Utils;

use App\Entity\Product;
use App\Entity\ProductsGroup;
use App\Entity\Unit;

class UnitUtils
{
    public static function checkUnit(?ProductsGroup $productsGroup, ?Product $product, Unit $unit): bool
    {
        /** @var ProductsGroup|Product $position */
        $position = $productsGroup ?? $product;
        $u = $position->getUnit();
        $mainUnit = $u->getMain();
        if ($mainUnit !== null) {
            $u = $mainUnit;
        }
        $availableUnits = array_map(static fn(Unit $unit): int => $unit->getId(), $u->getUnits()->toArray());
        $availableUnits[] = $u->getId();

        return in_array($unit->getId(), $availableUnits, true);
    }

    public static function findGreaterUnit(Unit $unit1, Unit $unit2): Unit
    {
        $ratio1 = $unit1->getMain() !== null ? $unit1->getConverter() : 1;
        $ratio2 = $unit2->getMain() !== null ? $unit2->getConverter() : 1;

        return $ratio1 < $ratio2 ? $unit1 : $unit2;
    }

    public static function getUnitList(Unit $unit): array
    {
        $unit = $unit->getMain() ?? $unit;
        $units = $unit->getUnits()->toArray();
        $units[] = $unit;

        return $units;
    }

    public static function parseAmount(float $amount, Unit $baseUnit, Unit $selectedUnit): float
    {
        if ($baseUnit->getMain() !== null) {
            $amount /= $baseUnit->getConverter();
        }
        if ($selectedUnit->getMain() !== null) {
            $amount *= $selectedUnit->getConverter();
        }

        return $amount;
    }
}
