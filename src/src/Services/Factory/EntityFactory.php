<?php

namespace App\Services\Factory;

use App\Entity\User;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

abstract class EntityFactory
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

    protected function saveEntity(object $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
