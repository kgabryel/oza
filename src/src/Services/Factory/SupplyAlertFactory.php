<?php

namespace App\Services\Factory;

use App\Entity\Supply;
use App\Entity\SupplyAlert;
use App\Model\Form\SupplyAlert as AlertModel;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class SupplyAlertFactory extends EntityFactory
{
    public function create(FormInterface $form, Request $request, Supply $supply): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var AlertModel $data */
        $data = $form->getData();
        $data->setSupply($supply);
        $alert = new SupplyAlert();
        $alert->setAmount($data->getAmount());
        $alert->setSupply($supply);
        $alert->setAlert($data->getAlert());
        $alert->setUnit($data->getUnit());
        $this->saveEntity($alert);

        return true;
    }
}
