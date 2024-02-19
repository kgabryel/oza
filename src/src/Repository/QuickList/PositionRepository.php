<?php

namespace App\Repository\QuickList;

use App\Entity\QuickList\Position;
use App\Entity\User;
use App\Repository\FindForUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Position|null find($id, $lockMode = null, $lockVersion = null)
 * @method Position|null findOneBy(array $criteria, array $orderBy = null)
 * @method Position[]    findAll()
 * @method Position[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PositionRepository extends ServiceEntityRepository implements FindForUser
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Position::class);
    }

    public function findById($id, User $user): ?Position
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->innerJoin('e.list', 'l')
            ->where('l.user = :user')
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
            ->innerJoin('e.list', 'l')
            ->where('l.user = :id')
            ->setParameter('id', $user->getId())
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
