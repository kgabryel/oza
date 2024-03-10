<?php

namespace App\Services\Entity;

use App\Config\Message\ShoppingMessages;
use App\Controller\Web\BaseController;
use App\Entity\Shopping;
use App\Repository\ShoppingRepository;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ShoppingService extends EntityService
{
    private Shopping $shopping;
    private ShoppingRepository $shoppingRepository;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        UserService $userService,
        ShoppingRepository $shoppingRepository
    ) {
        parent::__construct($flashBag, $entityManager, $userService);
        $this->shoppingRepository = $shoppingRepository;
    }

    public function find(int $id): bool
    {
        $shopping = $this->shoppingRepository->findById($id, $this->user);

        if ($shopping === null) {
            return false;
        }
        $this->shopping = $shopping;

        return true;
    }

    public function getShopping(): Shopping
    {
        return $this->shopping;
    }

    public function remove(): void
    {
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, ShoppingMessages::DELETE_CORRECT);
        $this->removeEntity($this->shopping);
    }
}
