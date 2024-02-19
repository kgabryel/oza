<?php

namespace App\ViewData\Supplies;

use App\Config\ViewParameters;
use App\Entity\SupplyPart;
use App\ViewData\ViewData;
use Symfony\Component\Form\FormInterface;

class SupplyPartViewData extends ViewData
{
    public function addEntity(SupplyPart $supplyPart): self
    {
        $this->options[ViewParameters::ENTITY] = $supplyPart;

        return $this;
    }

    public function addSupplyPartForm(FormInterface $form): self
    {
        $this->options[ViewParameters::SUPPLY_PART_FORM] = $form->createView();

        return $this;
    }
}
