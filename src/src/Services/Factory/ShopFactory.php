<?php

namespace App\Services\Factory;

use App\Config\Message\ShopsMessages;
use App\Controller\Web\BaseController;
use App\Entity\Shop;
use App\Model\Form\Shop as ShopModel;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ShopFactory extends EntityFactory
{
    public function create(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var ShopModel $data */
        $data = $form->getData();
        $shop = new Shop();
        $shop->setUser($this->user);
        $shop->setName($data->getName());
        $shop->setDescription($data->getDescription());
        $this->saveEntity($shop);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, ShopsMessages::CREATED_CORRECTLY);

        return true;
    }
}
