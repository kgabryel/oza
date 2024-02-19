<?php

namespace App\Services\Transformer;

use App\Entity\QuickList\ClipboardPosition;

class QuickListClipboardPositionTransformer
{
    public static function toArray(ClipboardPosition $clipboardPosition): array
    {
        return [
            'id' => $clipboardPosition->getId(),
            'name' => $clipboardPosition->getContent()
        ];
    }
}
