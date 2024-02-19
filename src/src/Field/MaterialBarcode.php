<?php

namespace App\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MaterialBarcode extends AbstractType
{
    public function getParent(): string
    {
        return TextType::class;
    }
}
