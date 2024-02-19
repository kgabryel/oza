<?php

namespace App\Repository;

use App\Entity\SupplyGroup;
use App\Entity\User;
use App\Model\Filter\SupplyGroup as Model;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SupplyGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupplyGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupplyGroup[]    findAll()
 * @method SupplyGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupplyGroupRepository extends ServiceEntityRepository implements FilterForUser, FindForUser
{
    use FilterForUserTrait;
    use FindTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SupplyGroup::class);
    }

    public function filter(User $user, Model $supplyGroup): array
    {
        $builder = $this->createQueryBuilder('e')
            ->where('e.user = :user_id')
            ->setParameter('user_id', $user->getId());
        if ($supplyGroup->getName() !== '') {
            $builder->andWhere('lower(e.name) like lower(:name)')
                ->setParameter('name', sprintf('%%%s%%', $supplyGroup->getName()));
        }
        if (!$supplyGroup->getProductsGroups()->isEmpty()) {
            $builder->innerJoin('e.supplies', 's')
                ->andWhere('s.group in (:groups)')
                ->setParameter('groups', $supplyGroup->getProductsGroups());
        }

        return $builder->getQuery()->getResult();
    }
}
