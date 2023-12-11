<?php

namespace App\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class WysiwygDataTransformer implements DataTransformerInterface
{
    public function reverseTransform($value)
    {
        if (!is_string($value)) {
            return $value;
        }
        return str_replace('<p>', '<p class="mb-0">', $value);
    }

    public function transform($value)
    {
        return $value;
    }
}
