<?php

namespace App\Services\Entity;

use App\Config\Message\ListMessages;
use App\Config\Settings;
use App\Controller\Web\BaseController;
use App\Entity\QuickList\QuickList;
use App\Model\Form\QuickList as QuickListModel;
use App\Repository\QuickList\ListRepository;
use App\Services\Factory\QuickList\ClipboardPositionFactory;
use App\Services\Factory\QuickList\PositionFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class QuickListService extends EntityService
{
    private ListRepository $listRepository;
    private QuickList $quickList;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        ListRepository $listRepository
    ) {
        parent::__construct($flashBag, $entityManager, $tokenStorage);
        $this->listRepository = $listRepository;
    }

    public function find(int $id): bool
    {
        $quickList = $this->listRepository->findById($id, $this->user);

        if ($quickList === null) {
            return false;
        }
        $this->quickList = $quickList;

        return true;
    }

    public function getList(): QuickList
    {
        return $this->quickList;
    }

    public function update(FormInterface $form, Request $request, PositionFactory $positionFactory): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var QuickListModel $data */
        $data = $form->getData();
        $this->entityManager->beginTransaction();
        foreach ($this->quickList->getPositions() as $position) {
            $this->entityManager->remove($position);
        }
        $this->entityManager->flush();
        $this->quickList->setName($data->getName());
        $this->quickList->setNote($data->getNote());
        foreach ($data->getPositions() as $position) {
            $position = $positionFactory->createFromModel($position);
            $this->quickList->addPosition($position);
        }
        $this->saveEntity($this->quickList);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, ListMessages::UPDATE_CORRECT);
        $this->entityManager->commit();

        return true;
    }

    public function remove(SessionInterface $session, ClipboardPositionFactory $clipboardPositionFactory): void
    {
        $this->entityManager->beginTransaction();
        if (!$session->get(Settings::DELETE_UNCHECKED_POSITIONS_QUICK)) {
            foreach ($this->quickList->getPositions() as $position) {
                if ($position->isChecked()) {
                    continue;
                }
                $clipboardPositionFactory->create($position);
            }
        }
        $this->removeEntity($this->quickList);
        $this->entityManager->commit();
    }
}
