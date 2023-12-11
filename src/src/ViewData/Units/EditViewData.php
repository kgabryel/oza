<?php

namespace App\ViewData\Units;

use App\Config\ViewParameters;
use App\Entity\Unit;
use App\ViewData\ViewData;
use Symfony\Component\Form\FormInterface;

class EditViewData extends ViewData
{
    public function addEntity(Unit $unit): self
    {
        $this->options[ViewParameters::ENTITY] = $unit;

        return $this;
    }

    public function addForm(FormInterface $form): self
    {
        $this->options[ViewParameters::FORM] = $form->createView();

        return $this;
    }
}
