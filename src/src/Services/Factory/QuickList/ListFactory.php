<?php

namespace App\Services\Factory\QuickList;

use App\Config\Message\ListMessages;
use App\Controller\Web\BaseController;
use App\Entity\QuickList\QuickList;
use App\Model\Form\QuickList as QuickListModel;
use App\Services\Factory\EntityFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ListFactory extends EntityFactory
{
    public function create(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var QuickListModel $data */
        $data = $form->getData();
        $list = new QuickList();
        $list->setUser($this->user);
        $list->setName($data->getName());
        $list->setNote($data->getNote());
        $positionFactory = new PositionFactory($this->entityManager);
        $positionFactory->setList($list);
        foreach ($data->getPositions() as $positionData) {
            $positionFactory->create($positionData);
        }
        $this->saveEntity($list);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, ListMessages::CREATED_CORRECTLY);

        return true;
    }
}
