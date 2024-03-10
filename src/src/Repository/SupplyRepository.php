<?php

namespace App\Repository;

use App\Entity\Supply;
use App\Entity\User;
use App\Model\Filter\Supply as Model;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Supply|null find($id, $lockMode = null, $lockVersion = null)
 * @method Supply|null findOneBy(array $criteria, array $orderBy = null)
 * @method Supply[]    findAll()
 * @method Supply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupplyRepository extends ServiceEntityRepository implements FindForUser
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Supply::class);
    }

    public function filter(User $user, Model $supply): array
    {
        $builder = $this->createQueryBuilder('e')
            ->innerJoin('e.group', 'g')
            ->where('g.user = :user_id')
            ->setParameter('user_id', $user->getId());
        if ($supply->getAmountMin() !== null) {
            $builder->andWhere('e.amount >= :amountMin')
                ->setParameter('amountMin', $supply->getAmountMin());
        }
        if ($supply->getAmountMax() !== null) {
            $builder->andWhere('e.amount >= :amountMax')
                ->setParameter('amountMax', $supply->getAmountMax());
        }
        if (!$supply->getProductsGroups()->isEmpty()) {
            $builder->andWhere('e.group in (:groups)')
                ->setParameter('groups', $supply->getProductsGroups());
        }
        if (!$supply->getUnits()->isEmpty()) {
            $builder->andWhere('g.unit in (:units)')->setParameter('units', $supply->getUnits());
        }
        if (!$supply->getGroups()->isEmpty()) {
            $builder->innerJoin('e.supplyGroups', 'sg')
                ->andWhere('sg.id in (:groups)')
                ->setParameter('groups', $supply->getGroups());
        }

        return $builder->getQuery()->getResult();
    }

    public function findById(int $id, User $user): ?Supply
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e.group', 'g')
            ->where('g.user = :user_id')
            ->andWhere('e.id = :id')
            ->setParameter('id', $id)
            ->setParameter('user_id', $user->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param  User  $user
     *
     * @return Supply[]
     */
    public function findForUser(User $user): array
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e.group', 'g')
            ->where('g.user = :user_id')
            ->setParameter('user_id', $user->getId())
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
