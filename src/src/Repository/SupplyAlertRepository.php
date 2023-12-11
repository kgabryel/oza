<?php

namespace App\Repository;

use App\Entity\Supply;
use App\Entity\SupplyAlert;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SupplyAlert|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupplyAlert|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupplyAlert[]    findAll()
 * @method SupplyAlert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupplyAlertRepository extends ServiceEntityRepository implements FindForUser
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SupplyAlert::class);
    }

    public function findAlertToActivate(float $amount, Supply $supply): ?SupplyAlert
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->innerJoin('e.supply', 's')
            ->where('e.amount >= :amount')
            ->andWhere('e.supply = :supply')
            ->setParameter('amount', $amount)
            ->setParameter('supply', $supply->getId())
            ->orderBy('e.amount', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findById($id, User $user): ?SupplyAlert
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->innerJoin('e.alert', 'a')
            ->where('a.user = :user')
            ->andWhere('e.id = :id')
            ->setParameter('user', $user->getId())
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $supply
     *
     * @return SupplyAlert[]
     */
    public function findForSupply(int $supply): array
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->innerJoin('e.supply', 's')
            ->andWhere('e.supply = :supply')
            ->setParameter('supply', $supply)
            ->getQuery()
            ->getResult();
    }

    public function findForUser(User $user): array
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->innerJoin('e.alert', 'a')
            ->where('a.user = :id')
            ->setParameter('id', $user->getId())
            ->getQuery()
            ->getResult();
    }

    public function getPreviousAlert(SupplyAlert $alert): ?SupplyAlert
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->where('e.supply = :supply')
            ->andWhere('e.amount < :amount')
            ->setParameter('supply', $alert->getSupply()->getId())
            ->setParameter('amount', $alert->getAmount())
            ->orderBy('e.amount', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
