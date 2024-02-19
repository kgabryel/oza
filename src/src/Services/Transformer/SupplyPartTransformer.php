<?php

namespace App\Services\Transformer;

use App\Entity\SupplyPart;

class SupplyPartTransformer
{
    public static function toArray(SupplyPart $supplyPart): array
    {
        return [
            'id' => $supplyPart->getId(),
            'description' => $supplyPart->getDescription(),
            'amount' => $supplyPart->getAmount(),
            'part' => $supplyPart->getPart(),
            'unit' => [
                'id' => $supplyPart->getUnit()->getId(),
                'name' => $supplyPart->getUnit()->getName(),
                'shortcut' => $supplyPart->getUnit()->getShortcut()
            ],
            'product' => [
                'id' => $supplyPart->getProduct()?->getId(),
                'name' => (string)$supplyPart->getProduct()
            ],
            'updatedAt' => $supplyPart->getUpdatedAt()?->format('Y-m-d H:i'),
            'dateOfConsumption' => $supplyPart->getDateOfConsumption()?->format('Y-m-d'),
            'open' => $supplyPart->isOpen()
        ];
    }
}
