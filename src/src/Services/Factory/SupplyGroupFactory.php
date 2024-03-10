<?php

namespace App\Services\Factory;

use App\Config\Message\SupplyGroupsMessages;
use App\Controller\Web\BaseController;
use App\Entity\SupplyGroup;
use App\Model\Form\SupplyGroup as SupplyGroupModel;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class SupplyGroupFactory extends EntityFactory
{
    public function create(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var SupplyGroupModel $data */
        $data = $form->getData();
        $shop = new SupplyGroup();
        $shop->setUser($this->user)
            ->setName($data->getName());
        $this->saveEntity($shop);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, SupplyGroupsMessages::CREATED_CORRECTLY);

        return true;
    }
}
