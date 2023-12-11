<?php

namespace App\Validator\ConnectedUnit;

use App\Config\Message\Error\SupplyErrors;
use App\Entity\ProductsGroup;
use Symfony\Component\Form\Form;

class SupplyConnectedUnit extends ConnectedUnit
{
    protected function getConnectedField(): Form
    {
        return $this->context->getRoot()['productsGroup'];
    }

    protected function getError(): string
    {
        return SupplyErrors::INVALID_UNIT;
    }

    protected function getProductGroup(): ProductsGroup
    {
        return $this->connectedField->getData();
    }
}
