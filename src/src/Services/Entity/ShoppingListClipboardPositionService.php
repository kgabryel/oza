<?php

namespace App\Services\Entity;

use App\Entity\ShoppingList\ClipboardPosition;
use App\Repository\ShoppingList\ClipboardPositionRepository;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ShoppingListClipboardPositionService extends EntityService
{
    private ClipboardPosition $clipboardPosition;
    private ClipboardPositionRepository $clipboardPositionRepository;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        UserService $userService,
        ClipboardPositionRepository $clipboardPositionRepository
    ) {
        parent::__construct($flashBag, $entityManager, $userService);
        $this->clipboardPositionRepository = $clipboardPositionRepository;
    }

    public function find(int $id): bool
    {
        $clipboardPosition = $this->clipboardPositionRepository->findOneBy([
            'user' => $this->user,
            'id' => $id
        ]);

        if ($clipboardPosition === null) {
            return false;
        }
        $this->clipboardPosition = $clipboardPosition;

        return true;
    }

    public function remove(): void
    {
        $this->removeEntity($this->clipboardPosition);
    }
}
