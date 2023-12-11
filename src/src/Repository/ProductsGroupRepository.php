<?php

namespace App\Repository;

use App\Entity\ProductsGroup;
use App\Entity\Supply;
use App\Entity\User;
use App\Model\Filter\ProductsGroup as Model;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductsGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductsGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductsGroup[]    findAll()
 * @method ProductsGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method ProductsGroup|null findById($id, User $user)
 */
class ProductsGroupRepository extends ServiceEntityRepository implements FilterForUser, FindForUser
{
    use FilterForUserTrait;
    use FindTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductsGroup::class);
    }

    public function filter(User $user, Model $productsGroup): array
    {
        $builder = $this->createQueryBuilder('e')
            ->where('e.user = :user_id')
            ->setParameter('user_id', $user->getId());
        if ($productsGroup->getName() !== '') {
            $builder->andWhere('lower(e.name) like lower(:name)')
                ->setParameter('name', sprintf('%%%s%%', $productsGroup->getName()));
        }
        if (!$productsGroup->getUnits()->isEmpty()) {
            $builder->andWhere('e.unit in (:units)')->setParameter('units', $productsGroup->getUnits());
        }

        return $builder->getQuery()->getResult();
    }

    public function findWithSupply(User $user): array
    {
        $qb = $this->_em->createQueryBuilder();
        $subQuery = $qb->select('g.id')
            ->from(Supply::class, 's')
            ->innerJoin('s.group', 'g')
            ->groupBy('g.id')
            ->getDQL();

        return $this->createQueryBuilder('e')
            ->where('e.user = :user_id')
            ->andWhere($qb->expr()->in('e.id', $subQuery))
            ->setParameter('user_id', $user->getId())
            ->getQuery()
            ->getResult();
    }

    public function findWithoutSupply(User $user): array
    {
        $qb = $this->_em->createQueryBuilder();
        $subQuery = $qb->select('g.id')
            ->from(Supply::class, 's')
            ->innerJoin('s.group', 'g')
            ->groupBy('g.id')
            ->getDQL();

        return $this->createQueryBuilder('e')
            ->where('e.user = :user_id')
            ->andWhere($qb->expr()->notIn('e.id', $subQuery))
            ->setParameter('user_id', $user->getId())
            ->getQuery()
            ->getResult();
    }
}
