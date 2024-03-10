<?php

namespace App\Repository;

use App\Entity\Brand;
use App\Entity\User;
use App\Model\Filter\Brand as Model;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Brand>
 * @method Brand|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brand|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brand[]    findAll()
 * @method Brand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrandRepository extends ServiceEntityRepository implements FilterForUser, FindForUser
{
    use FilterForUserTrait;
    use FindTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brand::class);
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
