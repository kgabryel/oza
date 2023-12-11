<?php

namespace App\Repository;

use Symfony\Component\Security\Core\User\UserInterface;

trait FilterForUserTrait
{
    public function filterForUser(
        string $columnName,
        string $columnValue,
        string $userColumn,
        UserInterface $user,
        int $id
    ): array {
        $builder = $this->createQueryBuilder('e')
            ->select('e')
            ->where(sprintf('lower(e.%s) = lower(:value)', $columnName));

        return $builder->andWhere(sprintf('e.%s = :user', $userColumn))
            ->andWhere('e.id != :id')
            ->setParameter('id', $id)
            ->setParameter('value', $columnValue)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
