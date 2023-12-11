<?php

namespace App\Validator\ConnectedUnit;

use App\Config\Message\Error\ProductErrors;
use App\Entity\ProductsGroup;
use Symfony\Component\Form\Form;

class ProductConnectedUnit extends ConnectedUnit
{
    protected function getConnectedField(): Form
    {
        return $this->context->getRoot()['productsGroups'];
    }

    protected function getError(): string
    {
        return ProductErrors::INVALID_UNIT;
    }

    protected function getProductGroup(): ?ProductsGroup
    {
        return $this->connectedField->getData()[0] ?? null;
    }
}
