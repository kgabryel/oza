<?php

namespace App\Repository;

use App\Entity\User;

trait FindTrait
{
    public function findById($id, User $user): mixed
    {
        return $this->findOneBy(compact('id', 'user'));
    }

    public function findForUser(User $user): array
    {
        return $this->findBy(
            ['user' => $user],
            ['id' => 'DESC']
        );
    }
}
