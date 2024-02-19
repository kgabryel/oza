<?php

namespace App\ViewData\SupplyGroups;

use App\Config\ViewParameters;
use App\Entity\SupplyGroup;
use App\ViewData\ViewData;
use Symfony\Component\Form\FormInterface;

class EditViewData extends ViewData
{
    public function addEntity(SupplyGroup $supplyGroup): self
    {
        $this->options[ViewParameters::ENTITY] = $supplyGroup;

        return $this;
    }

    public function addForm(FormInterface $form): self
    {
        $this->options[ViewParameters::CREATE_FORM] = $form->createView();

        return $this;
    }
}
