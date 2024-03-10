<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;

interface FindForUser extends ServiceEntityRepositoryInterface
{
    public function findById(int $id, User $user): mixed;

    public function findForUser(User $user): array;
}
