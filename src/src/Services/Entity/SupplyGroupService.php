<?php

namespace App\Services\Entity;

use App\Config\Message\SupplyGroupsMessages;
use App\Controller\Web\BaseController;
use App\Entity\SupplyGroup;
use App\Repository\SupplyGroupRepository;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class SupplyGroupService extends EntityService
{
    private SupplyGroup $supplyGroup;
    private SupplyGroupRepository $supplyGroupRepository;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        UserService $userService,
        SupplyGroupRepository $supplyGroupRepository
    ) {
        parent::__construct($flashBag, $entityManager, $userService);
        $this->supplyGroupRepository = $supplyGroupRepository;
    }

    public function find(int $id): bool
    {
        $supplyGroup = $this->supplyGroupRepository->findById($id, $this->user);

        if ($supplyGroup === null) {
            return false;
        }
        $this->supplyGroup = $supplyGroup;

        return true;
    }

    public function getSupplyGroup(): SupplyGroup
    {
        return $this->supplyGroup;
    }

    public function remove(): void
    {
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, SupplyGroupsMessages::DELETE_CORRECT);
        $this->removeEntity($this->supplyGroup);
    }

    public function update(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var SupplyGroup $data */
        $data = $form->getData();
        $this->supplyGroup->setName($data->getName());
        $this->saveEntity($this->supplyGroup);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, SupplyGroupsMessages::UPDATE_CORRECT);

        return true;
    }
}
