<?php

namespace App\Services\Factory;

use App\Config\Message\SupplyMessages;
use App\Controller\Web\BaseController;
use App\Entity\ProductsGroup;
use App\Entity\Supply;
use App\Model\Form\Supply as SupplyModel;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class SupplyFactory extends EntityFactory
{
    public function create(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var SupplyModel $data */
        $data = $form->getData();
        $supply = new Supply();
        $supply->setGroup($data->getProductsGroup());
        $supply->setDescription($data->getDescription());
        foreach ($data->getSupplyGroups() as $supplyGroup) {
            $supply->addSupplyGroup($supplyGroup);
        }
        $this->saveEntity($supply);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, SupplyMessages::CREATED_CORRECTLY);

        return true;
    }

    public function createForProductsGroup(ProductsGroup $productsGroup): void
    {
        $supply = new Supply();
        $supply->setGroup($productsGroup);
        $supply->setDescription('');
        $this->saveEntity($supply);
    }
}
