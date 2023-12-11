<?php

namespace App\Services\Transformer;

use App\Entity\Alert;

class AlertTransformer
{
    public static function toArray(Alert $alert): array
    {
        return [
            'id' => $alert->getId(),
            'description' => $alert->getDescription(),
            'type' => $alert->getType()->getType(),
            'typeName' => $alert->getType()->getName(),
            'isActive' => $alert->isActive()
        ];
    }
}
