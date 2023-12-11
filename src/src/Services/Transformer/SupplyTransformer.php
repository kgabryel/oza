<?php

namespace App\Services\Transformer;

use App\Entity\Supply;

class SupplyTransformer
{
    public static function toArray(Supply $supply): array
    {
        $groups = [];
        foreach ($supply->getSupplyGroups() as $group) {
            $groups[] = [
                'id' => $group->getId(),
                'name' => $group->getName()
            ];
        }

        return [
            'id' => $supply->getId(),
            'description' => $supply->getDescription(),
            'group' => [
                'id' => $supply->getGroup()->getId(),
                'name' => $supply->getGroup()->getName()
            ],
            'amount' => $supply->getAmount(),
            'unit' => [
                'id' => $supply->getGroup()->getBaseUnit()->getId(),
                'name' => $supply->getGroup()->getBaseUnit()->getName(),
                'shortcut' => $supply->getGroup()->getBaseUnit()->getShortcut()
            ],
            'alertsLength' => $supply->getAlerts()->count(),
            'updatedAt' => $supply->getUpdatedAt()?->format('Y-m-d H:i'),
            'groups' => $groups
        ];
    }
}
