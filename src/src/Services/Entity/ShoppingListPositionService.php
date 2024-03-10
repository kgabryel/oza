<?php

namespace App\Services\Entity;

use App\Entity\ShoppingList\Position;
use App\Entity\ShoppingList\ShoppingList;
use App\Model\Form\ChangeShop;
use App\Repository\ShoppingList\PositionRepository;
use App\Services\UserService;
use App\Utils\FormUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ShoppingListPositionService extends EntityService
{
    private Position $position;
    private PositionRepository $positionRepository;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        UserService $userService,
        PositionRepository $positionRepository
    ) {
        parent::__construct($flashBag, $entityManager, $userService);
        $this->positionRepository = $positionRepository;
    }

    public function changeShop(FormInterface $form, Request $request): bool
    {
        $form->submit(FormUtils::getJsonContent($request));
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var ChangeShop $data */
        $data = $form->getData();
        $this->position->setShop($data->getShop());
        $this->saveEntity($this->position);

        return true;
    }

    public function check(): bool
    {
        $this->position->check();
        $this->saveEntity($this->position);

        return true;
    }

    public function find(int $id): bool
    {
        $position = $this->positionRepository->findById($id, $this->user);

        if ($position === null) {
            return false;
        }
        $this->position = $position;

        return true;
    }

    public function getList(): ShoppingList
    {
        return $this->position->getList();
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function remove(): void
    {
        $this->removeEntity($this->position);
    }

    public function uncheck(): bool
    {
        $this->position->unCheck();
        $this->saveEntity($this->position);

        return true;
    }
}
