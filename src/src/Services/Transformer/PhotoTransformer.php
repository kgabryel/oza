<?php

namespace App\Services\Transformer;

use App\Entity\Photo;

class PhotoTransformer
{
    public static function toArray(Photo $photo, bool $selected = false, bool $deletable = false): array
    {
        return [
            'id' => $photo->getId(),
            'width' => $photo->getWidth(),
            'height' => $photo->getHeight(),
            'selected' => $selected,
            'deletable' => $deletable
        ];
    }
}
