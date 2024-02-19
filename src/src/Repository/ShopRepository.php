<?php

namespace App\Repository;

use App\Entity\Shop;
use App\Entity\User;
use App\Model\Filter\Shop as Model;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Shop|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shop|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shop[]    findAll()
 * @method Shop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopRepository extends ServiceEntityRepository implements FilterForUser, FindForUser
{
    use FilterForUserTrait;
    use FindTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shop::class);
    }

    public function filter(User $user, Model $shop): array
    {
        $builder = $this->createQueryBuilder('e')
            ->where('e.user = :user_id')
            ->setParameter('user_id', $user->getId());
        if ($shop->getName() !== '') {
            $builder->andWhere('lower(e.name) like lower(:name)')
                ->setParameter('name', sprintf('%%%s%%', $shop->getName()));
        }
        if ($shop->getDescription() !== '') {
            $builder->andWhere('lower(e.description) like lower(:description)')
                ->setParameter('description', sprintf('%%%s%%', $shop->getDescription()));
        }

        return $builder->getQuery()->getResult();
    }
}
