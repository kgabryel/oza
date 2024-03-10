<?php

namespace App\Services\Entity;

use App\Entity\User;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

abstract class EntityService
{
    protected EntityManagerInterface $entityManager;
    protected FlashBagInterface $flashBag;
    protected User $user;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        UserService $userService
    ) {
        $this->flashBag = $flashBag;
        $this->entityManager = $entityManager;
        $this->user = $userService->getUser();
    }

    abstract public function find(int $id): bool;

    protected function removeEntity(object $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    protected function saveEntity(object $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
