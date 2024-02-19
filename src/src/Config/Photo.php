<?php

namespace App\Config;

abstract class Photo
{
    private const DIMENSIONS = [
        'small' => [
            'width' => 100,
            'height' => 100
        ],
        'medium' => [
            'width' => 200,
            'height' => 200
        ]
    ];
    public const MIN_HEIGHT = 200;
    public const MIN_WIDTH = 200;

    public static function getHeight(PhotoType $type): int
    {
        return self::DIMENSIONS[$type->value]['height'];
    }

    public static function getWidth(PhotoType $type): int
    {
        return self::DIMENSIONS[$type->value]['width'];
    }
}
