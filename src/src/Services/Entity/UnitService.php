<?php

namespace App\Services\Entity;

use App\Config\Message\UnitMessages;
use App\Controller\Web\BaseController;
use App\Entity\Unit;
use App\Model\Form\EditUnit;
use App\Repository\UnitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UnitService extends EntityService
{
    private Unit $unit;
    private UnitRepository $unitRepository;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        UnitRepository $unitRepository
    ) {
        parent::__construct($flashBag, $entityManager, $tokenStorage);
        $this->unitRepository = $unitRepository;
    }

    public function find(int $id): bool
    {
        $unit = $this->unitRepository->findById($id, $this->user);

        if ($unit === null) {
            return false;
        }
        $this->unit = $unit;

        return true;
    }

    public function getUnit(): Unit
    {
        return $this->unit;
    }

    public function remove(): void
    {
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, UnitMessages::DELETE_CORRECT);
        $this->removeEntity($this->unit);
    }

    public function update(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var EditUnit $data */
        $data = $form->getData();
        $this->unit->setName($data->getName());
        $this->unit->setShortcut($data->getShortcut());
        $this->saveEntity($this->unit);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, UnitMessages::UPDATE_CORRECT);

        return true;
    }
}
