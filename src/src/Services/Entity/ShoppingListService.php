<?php

namespace App\Services\Entity;

use App\Config\Message\ListMessages;
use App\Config\Settings;
use App\Controller\Web\BaseController;
use App\Entity\ShoppingList\ShoppingList;
use App\Model\Form\ShoppingList as ShoppingListModel;
use App\Model\Form\ShoppingListPosition;
use App\Repository\ShoppingList\ListRepository;
use App\Services\Factory\ShoppingList\ClipboardPositionFactory;
use App\Services\Factory\ShoppingList\PositionFactory;
use App\Services\PositionMergeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ShoppingListService extends EntityService
{
    private ListRepository $listRepository;
    private PositionMergeService $mergeService;
    private ShoppingList $shoppingList;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        ListRepository $listRepository,
        PositionMergeService $mergeService
    ) {
        parent::__construct($flashBag, $entityManager, $tokenStorage);
        $this->listRepository = $listRepository;
        $this->mergeService = $mergeService;
    }

    public function find(int $id): bool
    {
        $shoppingList = $this->listRepository->findById($id, $this->user);

        if ($shoppingList === null) {
            return false;
        }
        $this->shoppingList = $shoppingList;

        return true;
    }

    public function getList(): ShoppingList
    {
        return $this->shoppingList;
    }

    public function remove(SessionInterface $session, ClipboardPositionFactory $clipboardPositionFactory): void
    {
        $this->entityManager->beginTransaction();
        if (!$session->get(Settings::DELETE_UNCHECKED_POSITIONS)) {
            foreach ($this->shoppingList->getPositions() as $position) {
                if ($position->isChecked()) {
                    continue;
                }
                $clipboardPositionFactory->create($position);
            }
        }
        $this->removeEntity($this->shoppingList);
        $this->entityManager->commit();
    }

    public function update(FormInterface $form, Request $request, PositionFactory $shoppingListPositionFactory): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var ShoppingListModel $data */
        $data = $form->getData();
        $data->setPositions(
            array_filter(
                $data->getPositions(),
                static fn(ShoppingListPosition $position) => $position->getProduct() !== null
                    || $position->getProductsGroup() !== null
            )
        );
        $this->shoppingList->setName($data->getName());
        $this->shoppingList->setNote($data->getNote() ?? '');
        $this->entityManager->beginTransaction();
        foreach ($this->shoppingList->getPositions() as $position) {
            $this->removeEntity($position);
        }
        $this->mergeService->setPositions($data->getPositions())->merge();
        foreach ($this->mergeService->getPositions() as $position) {
            $this->shoppingList->addPosition($shoppingListPositionFactory->create($position)->get());
        }
        $this->saveEntity($this->shoppingList);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, ListMessages::UPDATE_CORRECT);
        $this->entityManager->commit();

        return true;
    }
}
