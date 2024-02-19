<?php

namespace App\Services\Transformer;

use App\Entity\QuickList\Position;
use App\Entity\QuickList\QuickList;

class QuickListTransformer
{
    public static function toArray(QuickList $quickList): array
    {
        return [
            'id' => $quickList->getId(),
            'name' => $quickList->getName(),
            'note' => $quickList->getNote(),
            'createdAt' => $quickList->getCreatedAt()->format('Y-m-d H:i'),
            'positions' => array_map(
                static fn(Position $position): array => [
                    'id' => $position->getId(),
                    'content' => $position->getContent(),
                    'checked' => $position->isChecked()
                ],
                $quickList->getPositions()->toArray()
            )
        ];
    }
}
