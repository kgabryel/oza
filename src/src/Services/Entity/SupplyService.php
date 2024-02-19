<?php

namespace App\Services\Entity;

use App\Config\Message\SupplyMessages;
use App\Controller\Web\BaseController;
use App\Entity\Supply;
use App\Model\Form\EditSupply;
use App\Repository\SupplyRepository;
use App\Services\ExternalSuppliesService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SupplyService extends EntityService
{
    private Supply $supply;
    private SupplyRepository $supplyRepository;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        SupplyRepository $supplyRepository
    ) {
        parent::__construct($flashBag, $entityManager, $tokenStorage);
        $this->supplyRepository = $supplyRepository;
    }

    public function find(int $id): bool
    {
        $supply = $this->supplyRepository->findById($id, $this->user);

        if ($supply === null) {
            return false;
        }
        $this->supply = $supply;

        return true;
    }

    public function getSupply(): Supply
    {
        return $this->supply;
    }

    public function remove(ExternalSuppliesService $externalSuppliesService): void
    {
        $externalSuppliesService->disconnect($this->supply);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, SupplyMessages::DELETE_CORRECT);
        $this->removeEntity($this->supply);
    }

    public function update(
        FormInterface $form,
        Request $request,
        ExternalSuppliesService $externalSuppliesService
    ): bool {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var EditSupply $data */
        $data = $form->getData();
        $this->supply->setDescription($data->getDescription());
        $this->supply->clearGroups();
        foreach ($data->getSupplyGroups() as $group) {
            $this->supply->addSupplyGroup($group);
        }
        $this->saveEntity($this->supply);
        $externalSuppliesService->update($this->supply);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, SupplyMessages::UPDATE_CORRECT);

        return true;
    }
}
