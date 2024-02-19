<?php

namespace App\Services\Factory;

use App\Config\Message\UnitMessages;
use App\Controller\Web\BaseController;
use App\Entity\Unit;
use App\Model\Form\Unit as UnitModel;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class UnitFactory extends EntityFactory
{
    public function createUnit(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var UnitModel $data */
        $data = $form->getData();
        $unit = new Unit();
        $unit->setUser($this->user);
        $unit->setName($data->getName());
        $unit->setShortcut($data->getShortcut());
        if (!$data->getIsMainUnit()) {
            $unit->setConverter($data->getConverter());
            $unit->setMain($data->getMainUnit());
        }
        $this->saveEntity($unit);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, UnitMessages::CREATED_CORRECTLY);

        return true;
    }
}
