<?php

namespace App\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class WysiwygDataTransformer implements DataTransformerInterface
{
    public function reverseTransform(mixed $value): string
    {
        if (!is_string($value)) {
            return (string)$value;
        }

        return str_replace('<p>', '<p class="mb-0">', $value);
    }

    public function transform(mixed $value): ?string
    {
        return $value;
    }
}
