<?php

namespace App\Services\Transformer;

use App\Entity\SupplyGroup;

class SupplyGroupTransformer
{
    public static function toArray(SupplyGroup $supplyGroup): array
    {
        $supplies = [];
        foreach ($supplyGroup->getSupplies() as $supply) {
            $supplies[] = [
                'id' => $supply->getId(),
                'name' => $supply->getGroup()->getName()
            ];
        }

        return [
            'id' => $supplyGroup->getId(),
            'name' => $supplyGroup->getName(),
            'supplies' => $supplies
        ];
    }
}
