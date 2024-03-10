<?php

namespace App\Services\Factory\ShoppingList;

use App\Config\Message\ListMessages;
use App\Controller\Web\BaseController;
use App\Entity\ShoppingList\ShoppingList;
use App\Model\Form\ShoppingList as ShoppingListModel;
use App\Model\Form\ShoppingListPosition;
use App\Services\Factory\EntityFactory;
use App\Services\PositionMergeService;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ListFactory extends EntityFactory
{
    private PositionMergeService $mergeService;
    private PositionFactory $shoppingListPositionFactory;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        UserService $userService,
        PositionMergeService $mergeService,
        PositionFactory $shoppingListPositionFactory
    ) {
        parent::__construct($flashBag, $entityManager, $userService);
        $this->mergeService = $mergeService;
        $this->shoppingListPositionFactory = $shoppingListPositionFactory;
    }

    public function create(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var ShoppingListModel $data */
        $data = $form->getData();
        $list = new ShoppingList();
        $list->setUser($this->user)
            ->setName($data->getName())
            ->setNote($data->getNote() ?? '');
        $data->setPositions(
            array_filter(
                $data->getPositions(),
                static fn(ShoppingListPosition $position) => $position->getValue() !== null
            )
        );
        $this->mergeService->setPositions($data->getPositions())->merge();
        foreach ($this->mergeService->getPositions() as $position) {
            $list->addPosition($this->shoppingListPositionFactory->create($position)->get());
        }
        $this->saveEntity($list);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, ListMessages::CREATED_CORRECTLY);

        return true;
    }
}
