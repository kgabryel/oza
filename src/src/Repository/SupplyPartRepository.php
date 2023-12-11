<?php

namespace App\Repository;

use App\Entity\SupplyPart;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SupplyPart|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupplyPart|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupplyPart[]    findAll()
 * @method SupplyPart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupplyPartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SupplyPart::class);
    }

    public function findById($id, User $user): ?SupplyPart
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->innerJoin('e.unit', 'u')
            ->where('u.user = :user')
            ->andWhere('e.id = :id')
            ->setParameter('user', $user->getId())
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findForUser(User $user): array
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->innerJoin('e.unit', 'u')
            ->where('u.user = :user')
            ->setParameter('user', $user->getId())
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
