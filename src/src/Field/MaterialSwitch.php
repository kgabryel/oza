<?php

namespace App\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class MaterialSwitch extends AbstractType
{
    public function getParent(): string
    {
        return CheckboxType::class;
    }
}
