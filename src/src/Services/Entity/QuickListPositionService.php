<?php

namespace App\Services\Entity;

use App\Entity\QuickList\Position;
use App\Entity\QuickList\QuickList;
use App\Repository\QuickList\PositionRepository;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class QuickListPositionService extends EntityService
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

    public function getList(): QuickList
    {
        return $this->position->getList();
    }

    public function remove(): void
    {
        $this->removeEntity($this->position);
    }

    public function uncheck(): void
    {
        $this->position->unCheck();
        $this->saveEntity($this->position);
    }
}
