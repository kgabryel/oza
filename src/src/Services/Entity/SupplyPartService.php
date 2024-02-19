<?php

namespace App\Services\Entity;

use App\Config\Message\SupplyMessages;
use App\Controller\Web\BaseController;
use App\Entity\SupplyPart;
use App\Model\Form\SupplyPart as SupplyPartModel;
use App\Repository\SupplyPartRepository;
use App\Services\ExternalSuppliesService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SupplyPartService extends EntityService
{
    private SupplyPart $supplyPart;
    private SupplyPartRepository $supplyPartRepository;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        SupplyPartRepository $supplyPartRepository
    ) {
        parent::__construct($flashBag, $entityManager, $tokenStorage);
        $this->supplyPartRepository = $supplyPartRepository;
    }

    public function find(int $id): bool
    {
        $supplyPart = $this->supplyPartRepository->findById($id, $this->user);

        if ($supplyPart === null) {
            return false;
        }
        $this->supplyPart = $supplyPart;

        return true;
    }

    public function getSupplyId(): int
    {
        return $this->supplyPart->getSupply()->getId();
    }

    public function getSupplyPart(): SupplyPart
    {
        return $this->supplyPart;
    }

    public function remove(): void
    {
        $supply = $this->supplyPart->getSupply();
        $supply->setUpdatedAt(new DateTime());
        $this->saveEntity($supply);
        $this->removeEntity($this->supplyPart);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, SupplyMessages::DELETE_CORRECT);
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
        /** @var SupplyPartModel $data */
        $data = $form->getData();
        $this->supplyPart->setAmount($data->getAmount());
        $this->supplyPart->setDescription($data->getDescription() ?? '');
        $this->supplyPart->setUnit($data->getUnit());
        $this->supplyPart->setPart($data->getPart());
        $data->isOpen() ? $this->supplyPart->open() : $this->supplyPart->close();
        $this->supplyPart->setProduct($data->getProduct());
        $date = $data->getDateOfConsumption();
        if ($date !== null) {
            $this->supplyPart->setDateOfConsumption(DateTime::createFromFormat('Y-m-d', $date));
        } else {
            $this->supplyPart->setDateOfConsumption(null);
        }
        $this->saveEntity($this->supplyPart);
        $supply = $this->supplyPart->getSupply();
        $supply->setUpdatedAt(new DateTime());
        $this->saveEntity($supply);
        $externalSuppliesService->update($supply);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, SupplyMessages::UPDATE_CORRECT);

        return true;
    }
}
